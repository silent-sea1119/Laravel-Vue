<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// API Routes.

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1'], function () {

    // Auth routes
    Route::post('auth/login', 'AuthController@postLogin');
    Route::post('auth/register', 'AuthController@postRegister');
    Route::get('/user/verify/{id}{token}', 'AuthController@verifyUser');
    Route::post('auth/password', 'AuthController@postResetPassword');

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('auth/me', 'AuthController@getMe');
        Route::put('auth/me', 'AuthController@putMe');
        Route::post('auth/logout', 'AuthController@postLogout');

        // User routes.
        Route::group(['prefix' => 'user'], function () {
            // change password.
            Route::post('/change-password', 'UserController@changePassword');
            // Update user profile.
            Route::post('/update-profile', 'UserController@updateProfile');
            // get total cards, funds.
            Route::get('/total-cards-funds', 'UserController@getTotalCardsFunds');
            // Get cards.
            Route::get('/cards', 'UserController@getCards');
            // Get transactions.
            Route::get('/transactions', 'UserController@getTransactions');
            // add funds.
            Route::post('/add-funds', 'UserController@postAddFunds');
            // request-card
            Route::post('/request-card', 'UserController@postRequestCard');
            // update
            Route::post('/update-user', 'UserController@postUpdate');
            // card-requests
            Route::get('/card-requests', 'UserController@cardRequests');

        });

        // Admin routes.
        Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth:api', 'auth.level:admin']], function () {
            // Dashboard
            Route::get('/dashboard', 'AdminController@dashboard');

             // users routes
            Route::group(['prefix' => 'users'], function () {
                Route::post('/mark/{id}', 'UserController@markVerified');
                Route::get('/', 'UserController@index');
                Route::get('/{id}', 'UserController@show');
                Route::post('/', 'UserController@store');
                Route::post('/put/{id}', 'UserController@put');
                Route::delete('/{id}', 'UserController@destroy');

                // add-funds
                Route::post('/add-funds/{id}', 'UserController@addFunds');
            });

             // cards routes
             Route::group(['prefix' => 'cards'], function () {
                Route::get('/', 'CardController@index');
                Route::get('/{id}', 'CardController@show');
                Route::post('/', 'CardController@store');
                Route::post('/put/{id}', 'CardController@put');
                Route::delete('/{id}', 'CardController@destroy');
                Route::post('/import', 'CardController@import');
            });

            // default card.
            Route::group(['prefix' => 'default-cards'], function () {
                Route::get('/', 'CardController@indexWithOutRequest');
                Route::post('/', 'CardController@storeWithOutRequest');
                Route::post('/import', 'CardController@importWithoutCard');
            });

             // card requests routes
             Route::group(['prefix' => 'card-requests'], function () {
                Route::get('/', 'CardRequestController@index');
                Route::get('/{id}', 'CardRequestController@show');
                Route::delete('/{id}', 'CardRequestController@destroy');
                Route::post('/update-status/{id}', 'CardRequestController@updateStatus');
                Route::post('/deny/{id}', 'CardRequestController@deny');
            });

            // Transactions.
            Route::get('/transactions', 'AdminController@transactions');

        });
    });
});
