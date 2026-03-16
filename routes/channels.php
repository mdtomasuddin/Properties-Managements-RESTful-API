<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {
    return true;
});

Broadcast::channel('latest-message-channel.{conversationId}', function ($user, $conversationId) {
    return true;
});
