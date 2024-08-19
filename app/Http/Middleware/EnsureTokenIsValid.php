<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Services\JWTService;

class EnsureTokenIsValid
{

    public function handle(Request $request, Closure $next): Response
    {   
        $jwt = $request->bearerToken();
        
        if ($jwt) {

            $jwtService = new JWTService();
            $check = $jwtService->checkJWT($jwt);

            return $check !== true ? $check : $next($request);

        } else {
            return new JsonResponse([
                'jwt' => false,
                'message' => 'Authorization token is required.'
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }
    }

}
