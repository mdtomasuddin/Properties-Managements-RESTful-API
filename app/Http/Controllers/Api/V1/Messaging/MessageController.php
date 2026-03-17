<?php

namespace App\Http\Controllers\Api\V1\Messaging;

use App\Events\LatestMessageEvent;
use App\Events\MessageSentEvent;
use App\Helpers\Helper;
use App\Http\Requests\Api\Message\SendMessageRequest;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Participant;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController
{

    /**
     * @param int $id Receiver's user ID
     * Create a new private conversation or retrieve an existing one between the current user and the specified receiver.
     * @return JsonResponse
     */
    public function ConversationCreate($id): JsonResponse
    {
        try {
            $user = Auth::user();
            // Check if the conversation between users already exists
            $conversation = Conversation::whereHas('participants', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->whereHas('participants', function ($q) use ($id) {
                $q->where('user_id', $id);
            })->where('type', 'private')->first();

            // If no conversation exists, create a new one.
            if (! $conversation) {
                $conversation = Conversation::create(['type' => 'private']);
                $conversation->participants()->createMany([
                    ['user_id' => $user->id],
                    ['user_id' => $id],
                ]);
            }

            return Helper::jsonResponse(true, 'Conversation created successfully.', 201, $conversation);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to create conversation.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send a message to a conversation.
     *
     * @param SendMessageRequest $request
     * @param int $id Conversation ID
     * @return JsonResponse
     */
    public function sendMessage(SendMessageRequest $request, $id): JsonResponse
    {
        try {
            $user         = Auth::user();
            $message      = $request->input('message');
            $conversation = Conversation::find($id);

            // Check if the conversation exists
            if (! $conversation) {
                return Helper::jsonResponse(false, 'Conversation not found.', 404);
            }

            // Check if the authenticated user is a participant in the conversation
            $isParticipant = $conversation->participants()->where('user_id', $user->id)->exists();
            if (! $isParticipant) {
                return Helper::jsonResponse(false, 'Forbidden.', 403);
            }

            // Find the receiver
            $receiver = $conversation->participants()->where('user_id', '!=', $user->id)->first();
            if (! $receiver) {
                return Helper::jsonResponse(false, 'Receiver not found.', 404);
            }

            // File handling
            $uploadedFilePath = null;
            $type             = 'text';
            if ($request->hasFile('file_path')) {
                $file = $request->file('file_path');
                if ($file instanceof \Illuminate\Http\UploadedFile) {
                    $uploadedFilePath = Helper::fileUpload($file, 'MessageFiles');
                    $type             = 'file';
                }
            }

            // Create Message
            $messageData = Message::create([
                'sender_id'       => $user->id,
                'receiver_id'     => $receiver->user_id,
                'conversation_id' => $conversation->id,
                'message'         => $message,
                'file_path'       => $uploadedFilePath,
                'type'            => $type,
                'is_read'         => false,
            ]);

            // Count unread messages specifically for the receiver
            $unreadCount = Message::where('conversation_id', $conversation->id)
                ->where('receiver_id', $receiver->user_id)
                ->where('is_read', false)
                ->count();

            // Broadcast MessageSentEvent
            broadcast(new MessageSentEvent($messageData))->toOthers();
            
            // Broadcast LatestMessageEvent with unreadCount
            broadcast(new LatestMessageEvent($messageData, $unreadCount));

            return Helper::jsonResponse(true, 'Message sent successfully.', 201, $messageData);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Error.', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get messages for a specific conversation.
     *
     * @param int $id Conversation ID
     * @return JsonResponse
     */
    public function getChat($id): JsonResponse
    {
        try {
            $authUser     = Auth::user();
            $conversation = Conversation::find($id);
            if (! $conversation) {
                return Helper::jsonResponse(false, 'Conversation not found', 404);
            }
            
            // Check if user is a participant
            $isParticipant = Participant::where('conversation_id', $conversation->id)
                ->where('user_id', $authUser->id)
                ->exists();

            if (! $isParticipant) {
                return Helper::jsonResponse(false, 'Unauthorized Action.', 403);
            }

            // Mark messages as read
            Message::where('conversation_id', $conversation->id)
                ->where('sender_id', '!=', $authUser->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);

            // Fetch messages with sender details
            $messages = Message::with(['sender:id,name,avatar,role'])
                ->where('conversation_id', $conversation->id)
                ->orderBy('created_at', 'asc')
                ->get();

            return Helper::jsonResponse(true, 'Messages fetched successfully.', 200, $messages);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve messages', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get the authenticated user's conversations with participant details and unread message counts.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function conversations(Request $request): JsonResponse
    {
        $user = Auth::user();

        // Start a query for conversations
        $query = Conversation::whereHas('participants', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->with([
                'participants' => function ($query) use ($user) {
                    $query->with('user:id,name,avatar,role')->where('user_id', '!=', $user->id);
                },
                'lastMessage:id,sender_id,receiver_id,conversation_id,message,file_path,type,is_read,created_at',
            ])
            // Sort conversations by the last message's created_at timestamp
            ->orderByDesc(Message::select('created_at')->whereColumn('conversation_id', 'conversations.id')->limit(1));

        // Filter by participant name
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('participants.user', function ($q) use ($search, $user) {
                $q->where('id', '!=', $user->id)
                    ->where('name', 'like', '%' . $search . '%');
            });
        }

        // Get conversations
        $data = $query->get();

        // Calculate unread messages count per conversation
        $data->each(function ($conversation) use ($user) {
            $unreadCount = Message::where('conversation_id', $conversation->id)
                ->where('receiver_id', $user->id)
                ->where('is_read', false)
                ->count();
            $conversation->unread_count = $unreadCount;
        });

        return Helper::jsonResponse(true, 'Conversations fetched successfully.', 200, $data);
    }
}
