<?php

//php artisan install:api - para instalar dependencias api para laravel
//php artisan optimize - otimiza o MVC do projeto https://laravel.com/docs/11.x/deployment#optimization

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use App\Http\Controllers\UserController;
use App\Http\Middleware;


Route::post('/auth', [Controllers\AuthController::class, 'login']);

Route::prefix('/google')->group(function(){

    Route::get('/oauth', [Controllers\GoogleOAuthController::class, 'checkout']);
    Route::get('/oauth/callback', [Controllers\GoogleOAuthController::class, 'auth']);

});

Route::prefix('/user')->group(function(){

    Route::post('/register', [Controllers\UserController::class, 'store']);

    Route::post('/mail/change/password', [Controllers\UserController::class, 'mailForgetPassword']);
    Route::get('/check/token', [Controllers\UserController::class, 'verifyToken']);
    Route::post('/change/password', [Controllers\UserController::class, 'changePassword']);

    Route::put('/update', [UserController::class, 'update']);
    Route::get('/find', [UserController::class, 'find']);
    Route::delete('/delete', [UserController::class, 'removeUser']);

    Route::prefix('/address')->middleware(Middleware\EnsureTokenIsValid::class)->group(function (){

        Route::post('/add', [Controllers\AddressController::class, 'register']);
        Route::patch('/switch', [Controllers\AddressController::class, 'changeAddress']);
        Route::put('/update', [Controllers\AddressController::class, 'updateAddress']);
        Route::get('/find', [Controllers\AddressController::class, 'findAddress']);
        Route::get('/index', [Controllers\AddressController::class, 'indexAddress']);
        Route::delete('/remove', [Controllers\AddressController::class, 'removeAddress']);

    });

});

Route::prefix('/product')->middleware(App\Http\Middleware\EnsureTokenIsValid::class)->group(function(){

    Route::prefix('/type')->middleware(App\Http\Middleware\EnsureTokenIsValid::class)->group(function(){

        Route::post('/register', [Controllers\ProductTypeController::class, 'store']);
        Route::put('/update', [Controllers\ProductTypeController::class, 'update']);
        Route::get('/index', [Controllers\ProductTypeController::class, 'index']);

    });

    Route::get('/index', [Controllers\ProductController::class, 'index']);
    Route::get('/find', [Controllers\ProductController::class, 'show']);
    Route::post('/register', [Controllers\ProductController::class, 'store']);
    Route::put('/update', [Controllers\ProductController::class, 'update']);

});

Route::prefix('/card')->middleware(App\Http\Middleware\EnsureTokenIsValid::class)->group(function(){

    Route::post('/register', [Controllers\CardController::class, 'create']);
    Route::delete('/delete', [Controllers\CardController::class, 'destroy']);
    Route::get('/user', [Controllers\CardController::class, 'indexUserItems']);

});

Route::prefix('/item')->middleware(App\Http\Middleware\EnsureTokenIsValid::class)->group(function(){

    Route::post('/add', [Controllers\CardItemController::class, 'create']);
    Route::delete('/remove', [Controllers\CardItemController::class, 'remove']);
    Route::patch('/alter/qnt', [Controllers\CardItemController::class, 'alterItemQuantity']);

});

Route::prefix('/order')->group(function(){

    Route::post('/register', [Controllers\OrderController::class, 'create'])->middleware(App\Http\Middleware\EnsureTokenIsValid::class);
    Route::get('/success', [Controllers\OrderController::class, 'success']);
    Route::get('/cancel', [Controllers\OrderController::class, 'cancel']);

});

Route::post('/stripe/webhook/payment', [Controllers\StripeController::class, 'handlePayment']);
