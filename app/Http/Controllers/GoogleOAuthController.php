<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Google\Client as GoogleCLient;
use App\Services\GoogleOAuth;

class GoogleOAuthController extends Controller
{
    
    public function auth() : \Illuminate\Http\RedirectResponse
    {

        $google = new GoogleClient;

        return (new GoogleOAuth($google))->checkout();

    }

    public function callback(Request $request) : \Symfony\Component\HttpFoundation\Response
    {

        $google = new GoogleClient;

        return (new GoogleOAuth($google))->getUserData($request);

    }

}
