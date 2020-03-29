<?php

namespace App\Http\Controllers\API\Merchant;

use Illuminate\Http\Request;
use App\Product;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ProductController extends BaseController
{
    public function index(Request $request, $id){
        $product = Product::where('merchant_id',$id)->get();

        return $this->sendResponse($product, '');

    }

	public function store(Request $request){

        $validator = Validator::make($request->all(), [
            "name" 			=> "required",
            "price" 		=> "required",
            "img" 			=> "required|image|mimes:png,jpg,jpeg|max:1000",
            "description" 	=> "required",
            "stock" 		=> "required",
            "size"			=> "required"
        ]);

        if ($validator->fails()) {

           	return $this->sendError('Validation Error.', $validator->errors(), 500);

        }

        $image = null;

    	if($request->img != null){

        $image = 'Image-'.time().rand().'.'.$request->file('img')->getClientOriginalExtension();
        $request->file('img')->move(public_path().'/images/product/', $image);

        }

        $product = Product::create([
            'name'  		 => $request->name,
            'price'     	 => $request->price,
            'img'     		 => $image,
            'description'    => $request->description,
            'stock'    		 => $request->stock,
            'size'    		 => $request->size,
            'slug'    		 => Str::slug($request->name, "-"),
            'merchant_id'    => Auth::user()->merchant(),
        ]);

        if($product != false){

            $success = $product->toArray();

            return $this->sendResponse($success, 'Product stored successfully.');

        }else{

            return $this->sendError('Product stored error.','',500);  

        }
        
    }

    public function edit(Request $request, $id){
    	
        $validator = Validator::make($request->all(), [
            "name" 			=> "required",
            "price" 		=> "required",
            "description" 	=> "required",
            "stock" 		=> "required",
            "size"			=> "required"
        ]);

        if ($validator->fails()) {

           	return $this->sendError('Validation Error.', $validator->errors(), 500);

        }

        

    	if($request->img != null){
	    	$image = null;
	        $image = 'Image-'.time().rand().'.'.$request->file('img')->getClientOriginalExtension();
	        $request->file('img')->move(public_path().'/images/product/', $image);
	        $product['img'] = Product::find($id)->update([
	            'img' => $image,
	        ]);
        }

        $product = Product::find($id)->update([
            'name'  		 => $request->name,
            'price'     	 => $request->price,
            'description'    => $request->description,
            'stock'    		 => $request->stock,
            'size'    		 => $request->size,
            'slug'    		 => Str::slug($request->name, "-"),
            'merchant_id'    => Auth::user()->merchant(),
        ]);

        if($product != false){

            $success = $product;
            return $this->sendResponse($success, 'Product update successfully.');

        }else{

            return $this->sendError('Product update error.','',500);  

        }
        
    }
    public function delete(Request $request, $id){
        if(Product::find($id)->delete()){
            $success = true;
            return $this->sendResponse($success, 'Product delete successfully.');
        }else{
            return $this->sendError('Product delete error.','',500); 
        }
    }
}