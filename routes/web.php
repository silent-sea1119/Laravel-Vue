<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Verify user.
Route::get('/user/verify/{token}', 'App\Http\Controllers\Api\V1\AuthController@verifyUser');

// Coinbase webhook.
Route::group(['prefix' => 'webhook', 'namespace' => 'App\Http\Controllers'], function () {
    Route::post('/charge-completed', 'WebhookController@chargeCompleted');
    Route::post('/charge-failed', 'WebhookController@chargeFailed');
    Route::post('/charge-delayed', 'WebhookController@chargeDelayed');
});

Route::get('/charge', function() {
    $coinbase = new \App\Classes\Coinbase();
    $charge = $coinbase->charge([
        'customer_id' => 1,
        'email' => 'test@test.com',
        'name' => 'Test',
        'amount' => 100,
    ]);
    var_dump($charge);
});
Route::get('/{any?}', function () {
    return \File::get(public_path() . '/spa/index.html');
})->where(['any' => '.*']);
