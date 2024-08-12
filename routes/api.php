<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function(){
    return "Running..";
});

Route::prefix('/google')->group(function(){

    Route::get('/oauth', [App\Http\Controllers\GoogleOAuthController::class, 'checkout']);
    Route::post('/oauth/callback', [App\Http\Controllers\GoogleOAuthController::class, 'auth']);

});

Route::prefix('/user')->group(function(){

    Route::post('/register', [App\Http\Controllers\UserController::class, 'register']);

});