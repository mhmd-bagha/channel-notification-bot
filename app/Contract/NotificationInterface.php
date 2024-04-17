<?php

namespace App\Contract;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Telegram\Bot\Api;

interface NotificationInterface
{
    public function __construct(Api $telegram);

    public function sendMessageToChannel(Request $request): JsonResponse;
}
