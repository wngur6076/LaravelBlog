<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\SessionsController as ParentController;

class SessionsController extends ParentController
{
    protected function respondCreated($token)
    {
        return response()->json([
            'token' => $token,
        ], 201, [], JSON_PRETTY_PRINT);
    }

    protected function sendLockoutResponse(Request $request)
    {
        // 라라벨 5.3에만 적용되는 메서드
        $seconds = app(\Illuminate\Cache\RateLimiter::class)->availableIn(
            $this->throttleKey($request)
        );

        return json()->tooManyRequestsError("account_locked:for_{$seconds}_sec");
    }
}