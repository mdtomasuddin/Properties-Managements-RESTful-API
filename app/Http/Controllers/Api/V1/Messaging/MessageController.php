<?php

namespace App\Http\Controllers\Api\V1\Messaging;

use App\Events\LatestMassageEvent;
use App\Events\MessageSent;
use App\Helpers\Helper;
use App\Http\Requests\Api\Message\SendMessageRequest;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Participant;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController
{

    /**
     * @param int $id Receiver's user ID
     * Create a new private conversation or retrieve an existing one between the current user and the specified receiver.
     * @return JsonResponse
     * @return $Id
     */
    public function ConversationCreate($id)
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

            return Helper::jsonResponse(true, 'Conversation Created successfully.', 201, $conversation);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to send message.', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * sent
     * @param \App\Http\Requests\Api\Message\SendMessageRequest $sentRequest
     * @return JsonResponse
     * sending message both side user
     * revicer   send message
     */
    // public function sendMessage(SendMessageRequest $request, $id)
    // {
    //     try {
    //         $user     = Auth::user();
    //         $message  = $request->input('message');
    //         $filePath = $request->file('file_path');

    //         // Find the conversation by its ID
    //         $conversation = Conversation::find($id);
    //         // dd($conversation);
    //         if (! $conversation) {
    //             return Helper::jsonResponse(false, 'Conversation not found.', 404);
    //         }

    //         //Check if user is a participant
    //         $isParticipant = $conversation->participants()->where('user_id', $user->id)->exists();
    //         $recivrerId    = $conversation->participants()->where('user_id', '!=', $user->id)->first()->user_id;
    //         // dd($recivrerId);
    //         if (! $isParticipant) {
    //             return Helper::jsonResponse(false, 'You are not a participant in this conversation.', 403);
    //         }

    //         // Handle file upload or text message
    //         if ($filePath) {
    //             $uploadedFilePath = Helper::fileUpload($filePath, 'MessageFiles', $filePath);
    //             $data             = Message::create([
    //                 'sender_id'       => $user->id,
    //                 // 'receiver_id'     => $id,
    //                 'receiver_id'     => $recivrerId,
    //                 'conversation_id' => $conversation->id,
    //                 'message'         => $message,
    //                 'file_path'       => $uploadedFilePath,
    //                 'type'            => 'file',
    //                 'is_read'         => false,
    //             ]);
    //         } else {
    //             $data = Message::create([
    //                 'sender_id'       => $user->id,
    //                 // 'receiver_id'     => $id,
    //                 'receiver_id'     => $recivrerId,
    //                 'conversation_id' => $conversation->id,
    //                 'message'         => $message,
    //                 'file_path'       => null,
    //                 'type'            => 'text',
    //                 'is_read'         => false,
    //             ]);
    //         }
    //         //unread message count
    //         $unreadMessageCount = Message::where('conversation_id', $conversation->id)->where('receiver_id', $recivrerId)->where('is_read', false)->count();
    //         // dd($unreadMessageCount);

    //         // broadcast(new MessageSent($data))->toOthers();
    //         broadcast(new MessageSent($data));
    //         // unreadMessageCount event conversation id and receiver id send korbo
    //         // broadcast(new LatestMassageEvent())->toOthers();

    //         return Helper::jsonResponse(true, 'Message sent successfully.', 201, $data);
    //     } catch (Exception $e) {
    //         return Helper::jsonResponse(false, 'Failed to send message.', 500, [
    //             'error' => $e->getMessage(),
    //         ]);
    //     }
    // }

    public function sendMessage(SendMessageRequest $request, $id)
    {
        try {
            $user         = Auth::user();
            $message      = $request->input('message');
            $conversation = Conversation::findOrFail($id);

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
                $uploadedFilePath = Helper::fileUpload($request->file('file_path'), 'MessageFiles');
                $type             = 'file';
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
            $unreadCount = Message::where('conversation_id', $conversation->id)->where('receiver_id', $receiver->user_id)->where('is_read', false)->count();

            // Broadcast MessageSent Event
            broadcast(new MessageSent($messageData))->toOthers();
            //Broadcast LatestMassageEvent with unreadCount Event
            broadcast(new LatestMassageEvent($messageData, $unreadCount));
            return Helper::jsonResponse(true, 'Message sent.', 201, $messageData);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Error.', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get messages
     */
    public function getChat($id)
    {
        try {
            $authUser     = Auth::user();
            $conversation = Conversation::find($id);
            if (! $conversation) {
                return Helper::jsonResponse(false, 'Conversation not found', 404);
            }
            // Check if user is a participant
            $isParticipant = Participant::where('conversation_id', $conversation->id)->where('user_id', Auth::user()->id)->exists();

            if (! $isParticipant) {
                return Helper::jsonResponse(false, 'Unauthorized Action.', 403);
            }
            //is_read check and update
            Message::where('conversation_id', $conversation->id)->where('sender_id', '!=', $authUser->id)->where('is_read', false)->update(['is_read' => true]);

            // Fetch messages with sender details
            $messages = Message::with(['sender:id,name,avatar,role'])->where('conversation_id', $conversation->id)->orderBy('created_at', 'asc')->get();

            return Helper::jsonResponse(true, 'Messages fetched successfully.', 200, $messages);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to retrieve messages', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get conversations
     * Get the authenticated user's conversations with participant details and unread message counts.
     * @param Request $request
     * @return JsonResponse
     */
    public function conversations(Request $request)
    {
        $user = Auth::user();

        // Start a query for conversations
        $query = Conversation::whereHas('participants', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->with([
                'participants' => function ($query) {
                    $query->with('user:id,name,avatar,role')->where('user_id', '!=', Auth::user()->id);
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
                ->where('receiver_id', $user->id)->where('is_read', false)->count();
            $conversation->unread_count = $unreadCount; // Add unread count to each conversation
        });

        return Helper::jsonResponse(true, 'Conversations fetched successfully.', 200, $data);
    }
}
