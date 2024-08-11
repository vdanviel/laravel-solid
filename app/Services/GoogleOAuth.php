<?php

namespace App\Services;

use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;
use \Illuminate\Http\RedirectResponse;
use Google\Client as GoogleCLient;

//https://github.com/googleapis/google-api-php-client/blob/main/docs/oauth-web.md
final class GoogleOAuth {//final - https://www.php.net/manual/pt_BR/language.oop5.final.php

    private $client;

    public function __construct(GoogleClient $client)
    {   

        $this->client = $client;

        //https://github.com/googleapis/google-api-php-client/blob/main/docs/oauth-web.md#step-1-set-authorization-parameters
        $this->client->setAuthConfig(base_path('client_secret_723074063272-5d3jpqen3u56jv07sm908o19k81177b0.apps.googleusercontent.com.json'));//base_path() - https://laravel.com/docs/11.x/helpers#method-base-path
        $this->client->addScope([\Google\Service\Oauth2::USERINFO_PROFILE, \Google\Service\Oauth2::USERINFO_EMAIL]);
        $this->client->setRedirectUri(url('/api/google/oauth/callback'));//url() - https://laravel.com/docs/11.x/helpers#method-url
        $this->client->setAccessType('offline');//offline access
    }

    public function checkout() : RedirectResponse
    {
        
        //https://github.com/googleapis/google-api-php-client/blob/main/docs/oauth-web.md#step-2-redirect-to-googles-oauth-20-server
        $auth_url = $this->client->createAuthUrl();

        return redirect(filter_var($auth_url, FILTER_SANITIZE_URL));

    }

    //https://github.com/googleapis/google-api-php-client/blob/main/docs/oauth-web.md#step-4-handle-the-oauth-20-server-response
    public function getUserData(Request $request) : Response
    {

        $code = $request->query->get('code');

        if(isset($code) && !empty($code)){

            //https://github.com/googleapis/google-api-php-client/blob/main/docs/oauth-web.md#step-5-exchange-authorization-code-for-refresh-and-access-tokens
            $this->client->authenticate($code);

            $accessToken = $this->client->getAccessToken();

            //https://github.com/googleapis/google-api-php-client/blob/main/docs/oauth-web.md#calling-google-apis
            $this->client->setAccessToken($accessToken);

            $service = new \Google\Service\Oauth2($this->client);

            $guser = $service->userinfo->get();

            return response()->json([
                'status' => true,
                'name' => $guser->getFamilyName(),
                'email' => $guser->getEmail()
            ], Response::HTTP_OK, [
                'Content-Type: applicaiton/json'
            ]);

        }else{

            return response()->json([
                'status' => false,
                'message' => 'It was not possible to retrieve Google information.'
            ], Response::HTTP_BAD_REQUEST, [
                'Content-Type: applicaiton/json'
            ]);

        }

    }

}
