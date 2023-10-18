<?php

declare(strict_types=1);

namespace App\Services\Telegram\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class TelegramBotApiException extends Exception
{
    public function render(Request $request): JsonResponse
    {
        return response()->json([]);
    }

}