<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use \Google\Client as GoogleCLient;
use App\Services\GoogleOAuth;
use App\Models\User;

class GoogleOAuthController extends Controller
{
    
    public function auth() : \Illuminate\Http\RedirectResponse
    {

        $google = new GoogleClient;

        return (new GoogleOAuth($google))->checkout();

    }

    public function register(Request $request): Response
    {

        $google = new GoogleClient; 

        $googleUserData = (new GoogleOAuth($google))->getUserData($request);

        if(isset($googleUserData['error'])){
            
            return Response::json(['error'=>$googleUserData['error']], Response::HTTP_BAD_REQUEST);

        }else{


            $user = User::firstOrNew(
                ['email' => $googleUserData['email']],//looking for..
                ['name' => $googleUserData['name']]//others registering camps, if not find..
            );  

            if ($user->exists) {
                
                return Response::json([
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at,
                ], Response::HTTP_OK);

            }else{

                $user->save();

                return Response::json([
                    'registered' => $user->created_at,
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ], Response::HTTP_OK);

            }

        }
        
         
    }

}
