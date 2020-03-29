<?php

namespace App\Http\Middleware;
use Auth;
use Closure;
use App\Product;
use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
class IsProductMerchant extends BaseController
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
         $id = $request->route()->parameter('id');
         $product = Product::find($id);
         if($product != null){
             if($product->merchant_id == Auth::user()->merchant()) {
                return $next($request);
             }else{
                return $this->sendError('Forbidden to access', '', 407);
             }
        }

        return $this->sendError('Product not found', '', 404);  
    }
}
