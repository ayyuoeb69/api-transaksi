<?php


namespace App\Http\Controllers\API;


use Illuminate\Http\Request;

use App\Http\Controllers\API\BaseController as BaseController;

use App\User;

use App\Merchant;

use Illuminate\Support\Facades\Auth;

use Validator;

use JWTAuth;

use Tymon\JWTAuth\Exceptions\JWTException;

class RegisterController extends BaseController

{

    /**

     * Register api

     *

     * @return \Illuminate\Http\Response

     */

    public function register_customer(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'username'      => 'required',

            'name'          => 'required',

            'email'         => 'required|email',

            'password'      => 'required',

            'c_password'    => 'required|same:password',

        ]);


        if($validator->fails()){

            return $this->sendError('Validation Error.', $validator->errors(), 404);       

        }

        $user = User::create([
            'username'  => $request->username,
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => bcrypt($request['password']),
            'point'     => 0,
            'role'      => 2,
        ]);
        if($user != false){
            
            $success['token']    =  JWTAuth::fromUser($user);
            $success['username'] =  $user->username;
            $success['name']     =  $user->name;
            $success['email']    =  $user->email;
            $success['point']    =  $user->point;

            return $this->sendResponse($success, 'User Customer register successfully.');
        }else{

            return $this->sendError('Registation Error','',500);  

        }

    }


    public function register_merchant(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'username'      => 'required|unique:users',

            'name'          => 'required',

            'email'         => 'required|email|unique:users',

            'password'      => 'required',

            'c_password'    => 'required|same:password',

            'merchant_name'      => 'required',

            'merchant_logo'      => 'image|required|mimes:png,jpg,jpeg|max:1000',

        ]);


        if($validator->fails()){

            return $this->sendError('Validation Error.', $validator->errors(), 404);       

        }

        $user = User::create([
            'username'  => $request->username,
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => bcrypt($request['password']),
            'point'     => 0,
            'role'      => 1,

        ]);
        if($user != false){

            $logo = null;

            if($request->merchant_logo != null){
            $logo = 'Logo-'.time().rand().'.'.$request->file('merchant_logo')->getClientOriginalExtension();
            $request->file('merchant_logo')->move(public_path().'/images/logo/', $logo);
            }

            $merchant = Merchant::create([
                'name'      => $request->merchant_name,
                'address'   => $request->merchant_address,
                'logo'      => $logo,
                'user_id'   => $user->id,
            ]);

            $success['token']          =  JWTAuth::fromUser($user);
            $success['username']       =  $user->username;
            $success['name']           =  $user->name;
            $success['email']          =  $user->email;
            $success['point']          =  $user->point;
            $success['merchant_id']    =  $merchant->id;

            return $this->sendResponse($success, 'User Merchant register successfully.');
        }else{

            return $this->sendError('Registation Error','',500);  

        }

    }

}