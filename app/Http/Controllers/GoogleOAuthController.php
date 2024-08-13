<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use \Google\Client as GoogleCLient;
use App\Services\GoogleOAuth;
use App\Models\User;

class GoogleOAuthController extends Controller
{
    
    public function checkout() : \Illuminate\Http\RedirectResponse
    {

        $google = new GoogleClient;

        return (new GoogleOAuth($google))->checkout();

    }

    public function auth(Request $request): JsonResponse
    {

        $google = new GoogleClient; 

        $googleUserData = (new GoogleOAuth($google))->getUserData($request);

        if(isset($googleUserData['error'])){
            
            return new JsonResponse(['error'=>$googleUserData['error']], 400);

        }else{

            $user = User::firstOrNew(
                ['email' => $googleUserData['email']],//looking for..
                ['name' => $googleUserData['name']]//others registering camps, if not find..
            );  
                
            return new JsonResponse([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
            ], 200);

        }
        
         
    }

}
