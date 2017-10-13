<?php

namespace App\Http\Middleware;

use App\Helper\Traits\TokenTrait;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
//        if ($this->auth->guard($guard)->guest()) {
//            return response('Unauthorized.', 401);
//        }

        if (!$request->hasHeader('token-auth')){
            return response()->json([
                'status' => 401,
                'msg' => '请登录'
            ]);
        }

        if (!TokenTrait::checkToken($request->header('token-auth'))){
            return response()->json([
                'status' => 401,
                'msg'=>'登录已过期，请重新登录'
            ]);
        }
        $request->user_id = \Cache::get($request->header('token-auth'));
        return $next($request);
    }
}
