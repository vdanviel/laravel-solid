<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        
        $exceptions->render(function (\Throwable $th){

            //se o Throwable for do tipo validation....
            if ($th instanceof ValidationException) {
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage(),
                    'missing' => $th->errors()
                ], 422);
            }
    
            //se for outro tipo...
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);

        });

    })->create();
