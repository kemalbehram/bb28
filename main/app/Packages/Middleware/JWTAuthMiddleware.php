<?php

namespace App\Packages\Middleware;

use Closure;
use App\Models\AdminLog;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JWTAuthMiddleware extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard)
    {
        $user = auth($guard)->user();
        $user || real()->code(401)->exception();
        $user && $user->updateRequestedAt();

        if ($user->disable == 1) {
            real()->code(401)->exception();
        }

        if ($guard === 'users') {
            $token = $request->header('Authorization');
            $token = explode(' ', $token);
            $token = $token[1];

            if ($token !== $user->token) {
                real()->code(401)->exception();
            }
        }
        if ($guard === 'users') {
            $token = $request->header('Authorization');
            $token = explode(' ', $token);
            $token = $token[1];

            if ($token !== $user->token) {
                real()->code(401)->exception();
            }
        }
        if ($guard === 'admin' && $user->id != 1000) {
            $header = $request->header();
            $param  = [
                'referer'    => $header['referer'][0],
                'admin_id'   => $user->id,
                'user_agent' => $header['user-agent'][0],
                'path'       => $request->url(),
                'ip'         => $request->ip(),
                'created_at' => date('Y-m-d H:i:s'),
            ];
            AdminLog::create($param);
        }
        return $next($request);
    }
}
