<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;
use App\Services\ValidationService;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {

        $valid = ValidationService::dataValidation($request->all(), 
            [
                'email' => 'required|email',
                'password' => 'required'
            ]
        );

        if($valid instanceof JsonResponse) return $valid;
    
        try {

            return AuthService::authenticate($request, $request->email, $request->password);
    
        } catch (\Throwable $e) {

            return response()->json([
                'status' => false,
                'message' => 'An error occurred during login.',
                'error' => $e->getMessage(),
            ], 500);        

        }
    }
    
}
