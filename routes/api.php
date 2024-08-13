<?php

//php artisan install:api - para instalar dependencias api para laravel
//php artisan optimize - otimiza o MVC do projeto https://laravel.com/docs/11.x/deployment#optimization

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

Route::get('/', function(){
    return "Running..";
});

Route::prefix('/google')->group(function(){

    Route::get('/oauth', [Controllers\GoogleOAuthController::class, 'checkout']);
    Route::get('/oauth/callback', [Controllers\GoogleOAuthController::class, 'auth']);

});

Route::prefix('/user')->group(function(){

    Route::post('/register', [Controllers\UserController::class, 'store']);

});