<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function(){
    return "Running..";
});

Route::prefix('api')->group(function () {

    Route::prefix('google')->group(function(){

        Route::get('/oauth', [App\Http\Controllers\GoogleOAuthController::class, 'auth']);
        Route::get('/oauth/callback', [App\Http\Controllers\GoogleOAuthController::class, 'callback']);

    });

});
