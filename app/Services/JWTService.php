<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class JWTService {

    private string $secret;
    private string $hash;

    public function __construct(){
        $this->secret = env('SECRET_KEY_JWT');//https://laravel.com/docs/11.x/helpers#method-env;
        $this->hash = env('HASH_TYPE_JWT');
    }

    public function generateJWT(Request $request) : string
    {

        $result = [
            'iss' => url('/'),
            'aud' => $request->url(),
            'iat' => (new \DateTime())->getTimestamp(),
            'nbf' => (new \DateTime())->getTimestamp(),
            'exp' => now()->addMinute()->getTimestamp()
        ];

        $jwt = JWT::encode($result, $this->secret, $this->hash);

        return $jwt;

    }

    public function checkJWT(string $jwt) : JsonResponse | null
    {
        try {
            
            $decoded = JWT::decode($jwt, new Key($this->secret, $this->hash));
    
            if ((new \DateTime())->getTimestamp() > $decoded->exp) {
                return new JsonResponse(
                    [
                        'status' => false,
                        'message' => 'Your session has expired.'
                    ],
                    JsonResponse::HTTP_UNAUTHORIZED
                );
            }
    
            if ((new \DateTime())->getTimestamp() < $decoded->nbf) {
                return new JsonResponse(
                    [
                        'status' => false,
                        'message' => 'Token is not yet valid.'
                    ],
                    JsonResponse::HTTP_UNAUTHORIZED
                );
            }
    
            return null;
    
        } catch (\Exception $e) {

            return new JsonResponse(
                [
                    'status' => false,
                    'message' => 'Invalid token. ' . $e->getMessage()
                ],
                JsonResponse::HTTP_UNAUTHORIZED
            );

        }
    }
    
}