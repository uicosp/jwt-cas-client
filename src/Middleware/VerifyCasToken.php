<?php

namespace Uicosp\JwtCasClient\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\JWTAuth;

class VerifyCasToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            app(JWTAuth::class)->getJWTProvider()->setSecret(config('jwt-cas-client.jwt_secret'));
            if (!$payload = app(JWTAuth::class)->parseToken()->getPayload()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['token_expired'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid'], 400);
        } catch (JWTException $e) {
            return response()->json(['token_absent'], 400);
        }

        $request['verified_token'] = $payload->get();
        return $next($request);
    }
}
