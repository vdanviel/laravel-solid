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
    
        } catch (\Throwable $th) {

            return response()->json(
                [
                    'status' => false,
                    'message' => $th->getMessage(),
                    'trace' => $th->getTrace()
                ],
                500
            );     

        }
    }
    
}
