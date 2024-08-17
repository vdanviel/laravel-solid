<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\JWTService;

class AuthService
{

    public static function authenticate(Request $request, $email, $password) : JsonResponse
    {

        $user = User::where('email', $email)->first();

        if(!$user){

            return new JsonResponse(
                [
                    'status' => false,
                    'message' => "User doesn't exists.",
                ],
                JsonResponse::HTTP_UNAUTHORIZED
            );

        }

        if (Hash::check($password, $user->password)) {
            
            $jwtService = new JWTService();
            $jwt = $jwtService->generateJWT($request);

            return new JsonResponse(
                [
                    'status' => true,
                    'message' => 'Login successful.',
                    'token' => $jwt
                ],
                JsonResponse::HTTP_OK
            );

        }else{

            return new JsonResponse(
                [
                    'status' => false,
                    'message' => "Password is invalid.",
                ],
                JsonResponse::HTTP_UNAUTHORIZED
            );

        }

    }

}
