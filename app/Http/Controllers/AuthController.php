<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;
use App\Services\AuthValidationService;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {

        AuthValidationService::authDataValidation($request->all());
    
        try {

            return AuthService::authenticate($request->email, $request->password);
    
        } catch (\Throwable $e) {

            return response()->json([
                'status' => false,
                'message' => 'An error occurred during login.',
                'error' => $e->getMessage(),
            ], 500);        

        }
    }
    
}
