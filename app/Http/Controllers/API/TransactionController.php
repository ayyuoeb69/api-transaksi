<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Transaction;
use App\User;
use App\DetailTransaction;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;

class TransactionController extends BaseController
{
    public function index(Request $request){
        $transactions = Transaction::where('merchant_id',Auth::user()->merchant())->get();
        $i = 0;
        foreach ($transactions as $transaction) {
            $detail_transaction     = DetailTransaction::where('transaction_id',$transaction->id)->get();
            $transactions[$i]['detail'] = $detail_transaction;
            $i++;
        }
        
        return $this->sendResponse($transactions, '');

    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            "merchant_id" 		    => "required",
            "payment" 	            => "required",
            "address" 		        => "required",
            "postal_fee"			=> "required",
            "total_price"           => "required"
        ]);

        if ($validator->fails()) {

            return $this->sendError('Validation Error.', $validator->errors(), 500);

        }

        $product = Transaction::create([
            'customer_id'  		 =>  Auth::user()->id,
            'merchant_id'     	 => $request->merchant_id,
            'payment'            => $request->payment,
            'address'    		 => $request->address,
            'postal_fee'    	 => $request->postal_fee,
            'total_price'        => $request->total_price,
            'status'             => 1
        ]);

        if($product != false){

            for($i = 0;$i < count($request->product_id); $i++){
                DetailTransaction::create([
                    'product_id'         => $request->input('product_id.'.$i),
                    'transaction_id'     => $product->id,
                    'note'               => $request->input('note.'.$i),
                    'qty'                => $request->input('qty.'.$i),
                    'price'              => $request->input('price.'.$i),
                ]);
            }
            $point = Auth::user()->point + 5;
            User::find(Auth::user()->id)->update([
                'point' => $point
            ]);
            $success = $product->toArray();
            return $this->sendResponse($success, 'Transaction successfully.');

        }else{

            return $this->sendError('Transaction error.','',500);  

        }
        
    }

}