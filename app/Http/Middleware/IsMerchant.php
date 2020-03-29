<?php

namespace App\Http\Middleware;
use Auth;
use Closure;
use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
class IsMerchant extends BaseController
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
         if (Auth::user() &&  Auth::user()->role == 1) {
            return $next($request);
         }

        return $this->sendError('Forbidden to access', '', 403);  
    }
}
