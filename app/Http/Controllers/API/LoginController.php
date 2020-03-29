<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\API\BaseController as BaseController;

use Illuminate\Support\Facades\Auth;

use App\User;

use App\Merchant;

use Validator;

use JWTAuth;

use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends BaseController
{

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'email'         => 'required|email',

            'password'      => 'required'

        ]);


        if($validator->fails()){

            return $this->sendError('Validation Error.', $validator->errors(), 404);       

        }

        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        
        $success = Auth::user();
        $success['token'] = $token;
        if($success->role == 1){
            $merchant = Merchant::where('user_id',$success->id)->first();
            $success['merchant_id'] = $merchant->id;
        }
        return $this->sendResponse($success, 'Login successfully.');

    }
}