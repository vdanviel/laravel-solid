<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class JWTService {

    private string $secret;
    private string $hash;

    public function __construct(){

        //dd(config('jwt.secret'), config('jwt.hash'));

        $this->secret = config('jwt.secret');//https://laravel.com/docs/11.x/helpers#method-config
        $this->hash = config('jwt.hash');

    }

    public function generateJWT(Request $request) : string
    {

        $result = [
            'iss' => url('/'),
            'aud' => $request->url(),
            'iat' => (new \DateTime())->getTimestamp(),
            'nbf' => (new \DateTime())->getTimestamp(),
            'exp' => now()->addMonth()->getTimestamp()
        ];

        $jwt = JWT::encode($result, $this->secret, $this->hash);

        return $jwt;

    }

    public function checkJWT(string $jwt) : JsonResponse | bool
    {
        try {
            
            $decoded = JWT::decode($jwt, new Key($this->secret, $this->hash));
    
            if ((new \DateTime())->getTimestamp() > $decoded->exp) {
                return new JsonResponse(
                    [
                        'jwt' => 'Your session has expired.'
                    ],
                    JsonResponse::HTTP_UNAUTHORIZED
                );
            }
    
            if ((new \DateTime())->getTimestamp() < $decoded->nbf) {
                return new JsonResponse(
                    [
                        'jwt' => 'Session is unauthorized.',
                    ],
                    JsonResponse::HTTP_BAD_REQUEST
                );
            }
    
            return true;
    
        } catch (\Exception $e) {

            return new JsonResponse(
                [
                    'jwt' => $e->getMessage()
                ],
                JsonResponse::HTTP_UNAUTHORIZED
            );

        }
    }

}