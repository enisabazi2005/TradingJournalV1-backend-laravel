<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyMt5SyncToken
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $expectedToken = (string) config('services.mt5.sync_token');

        $providedToken = (string) $request->bearerToken();

        if (
            $expectedToken === ''
            || $providedToken === ''
            || ! hash_equals($expectedToken, $providedToken)
        ) {
            return response()->json([
                'message' => 'Unauthorized.',
            ], 401);
        }

        return $next($request);
    }
}