<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {
    return true;
});

Broadcast::channel('latest-message-channel.{conversationId}', function ($user, $conversationId) {
    // return (int) $user->id === (int) $receiverId;
    return true;
});

/**
 * condition check for participant user in conversation
 */
// Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {
//     return \App\Models\Conversation::whereHas('participants', function ($query) use ($user, $conversationId) {
//         $query->where('conversation_id', $conversationId)->where('user_id', $user->id);
//     })->exists();
// });
