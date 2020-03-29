<?php



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register/customer', 'API\RegisterController@register_customer'); // To register customer

Route::post('register/merchant', 'API\RegisterController@register_merchant'); // To register merchant

Route::post('login', 'API\LoginController@login'); // To login merchant and customer


Route::middleware('jwt.verify')->group( function () {
    Route::middleware('customer')->group( function () {
        Route::post('customer/transaction', 'API\TransactionController@store');
    });
});



Route::prefix('merchant')->group(function () {
   
    Route::get('transaction', 'API\TransactionController@index')->middleware('jwt.verify');
   
    Route::prefix('product')->group(function () {  

        Route::get('list-product/{id}', 'API\Merchant\ProductController@index');

        Route::middleware('jwt.verify')->group( function () {

            Route::middleware('merchant')->group( function () {

                Route::post('add', 'API\Merchant\ProductController@store');

                Route::middleware('product.merchant')->group( function () {

                    Route::put('update/{id}', 'API\Merchant\ProductController@edit');
                    Route::delete('delete/{id}', 'API\Merchant\ProductController@delete');

                });

            });

        });
    });
});
