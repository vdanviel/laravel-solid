<?php

//php artisan install:api - para instalar dependencias api para laravel
//php artisan optimize - otimiza o MVC do projeto https://laravel.com/docs/11.x/deployment#optimization

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use App\Http\Controllers\UserController;
use App\Http\Middleware;

Route::get('/', function(){
    return "Running..";
});

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

Route::prefix('/auth')->group(function(){

    Route::post('/', [Controllers\AuthController::class, 'login']);

});