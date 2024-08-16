<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserChangePassword;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use DateTime;

class UserService
{
    public static function createUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public static function putUser(User $user, array $data): bool
    {

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone_number = $data['phone_number'];

        return $user->save();

    }

    public static function registerAddressUser(User $user, array $data)
    {



    }
    
    public static function handleForgotPassword(Request $request) : JsonResponse
    {

        $user = User::where('email', $request->email)->first();

        if(!$user){

            return new JsonResponse(
                [
                    'status' => false,
                    'message' => "User doesn't exists.",
                ],
                JsonResponse::HTTP_UNAUTHORIZED
            );

        }

        $emailed = (new UserService)->sendEmailChangePassword($user);

        if ($emailed == true) {
            
            return new JsonResponse(
                [
                    'status' => true,
                    'message' => "Email was sended!",
                ],
                JsonResponse::HTTP_OK
            );

        }else{

            return new JsonResponse(
                [
                    'status' => true,
                    'message' => "Error on sending email.",
                ],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );

        }

    }

    public function generateUserToken(User $user) : string
    {

        $newToken = $user->createToken('change_password', ['read'], now()->addHour());

        $token = $newToken->plainTextToken;

        return $token;

    }

    public function sendEmailChangePassword(User $user) : bool
    {

        $token = $this->generateUserToken($user);

        try {

            Mail::to($user->email)->send(new UserChangePassword($user, $token));

            return true;
        } catch (\Throwable $th) {
            //echo $th;
            return false;
        }
        

    }
    
    public static function changePasswordUser(User $user, string $token, string $newPassword) : JsonResponse
    {

        $token_obj = PersonalAccessToken::where('tokenable_id', $user->id)->where('token', $token)->orderBy('tokenable_id', 'desc')->first();

        if (!$token_obj) {
            
            return new JsonResponse(
                [
                    'status' => false,
                    'message' => "Token is invalid.",
                ],
                JsonResponse::HTTP_UNAUTHORIZED
            );

        }

        $expired = (new UserService)->expiredRegisterToken($token_obj);

        if ($expired) {
            
            return new JsonResponse(
                [
                    'status' => false,
                    'message' => "Token is invalid.",
                ],
                JsonResponse::HTTP_UNAUTHORIZED
            );

        }else{

            $user->password = Hash::make($newPassword);

            $user->save();

            return new JsonResponse(
                [
                    'status' => true,
                    'message' => "You've changed your password successfully.",
                ],
                JsonResponse::HTTP_UNAUTHORIZED
            );

        }

    }

    public function expiredRegisterToken(PersonalAccessToken $personal_token_obj){

        $expiration_date = new DateTime($personal_token_obj->expires_at);

        $now = new DateTime();

        if ($now->getTimestamp() > $expiration_date->getTimestamp()) {
            
            return false;

        }else{

            return true;

        }

    }

     public static function patchUser(User $user, $data)
     {



     }

     public static function deleteUser(User $user, $id)
     {



     }
}
