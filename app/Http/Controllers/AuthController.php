<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Services\AuthService;

#request
use App\Http\Requests\LoginAuthRequest;

class AuthController extends Controller
{
    public function login(LoginAuthRequest $request): JsonResponse
    {
    
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
