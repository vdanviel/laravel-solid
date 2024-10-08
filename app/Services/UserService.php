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
    public static function createUser(array $data): JsonResponse
    {   

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'password' => Hash::make($data['password']),
        ]);

        return response()->json(
            [
                'status' => true,
                'message' => 'User created!'
            ],
            200
        );
    }

    public static function updateUser(int $user_id, array $data): JsonResponse
    {

        $user = User::find($user_id);

        if (!$user) {
            
            return new JsonResponse(
                [
                    'status' => false,
                    'message' => "User doesn't exists."
                ],
                JsonResponse::HTTP_UNAUTHORIZED
            );

        }

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone_number = $data['phone_number'];

        $result =  $user->save();

        if ($result == true) {
            
            return new JsonResponse(
                [
                    'status' => true,
                    'message' => 'User has been updated.'
                ],
                JsonResponse::HTTP_OK
            );

        }else{

            return new JsonResponse(
                [
                    'status' => false,
                    'message' => 'User has not been updated.'
                ],
                JsonResponse::HTTP_BAD_REQUEST  
            );

        }

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

    public static function findUser(int $idUser) : User
    {

        $user = User::find($idUser)->setHidden(['password', 'email_verified_at', 'remember_token']);

        return $user;

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
    
    public static function changePasswordUser(string $token, string $newPassword) : JsonResponse
    {

        $tokenObj = PersonalAccessToken::findToken($token);

        $tokenCondition = (new UserService)->verifyTokenCondition($token);

        if(isset($tokenCondition) && $tokenCondition['status'] == true){

            $user = User::find($tokenObj->tokenable_id);

            $user->password = Hash::make($newPassword);
    
            $user->save();
            
            $tokenObj->delete();

            return new JsonResponse(
                [
                    'status' => true,
                    'message' => "You've changed your password successfully!",
                ],
                JsonResponse::HTTP_OK
            );
            

        }else{

            return new JsonResponse(
                [
                    'status' => false,
                    'message' => $tokenCondition['reason'] ,
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );

        }

    }

    public static function verifyTokenCondition(string $token): array
    {   

        $tokenObj = PersonalAccessToken::findToken($token);

        if (!$tokenObj) {

            return [
                    'status' => false,
                    'reason' => "Token is invalid.",
                ];

        }

        $user = User::find($tokenObj->tokenable_id);

        if (!$user) {
            
            return [
                    'status' => false,
                    'reason' => "User doesn't exists.",
            ];

        }

        $expiration_date = new DateTime($tokenObj->expires_at);

        if ((new DateTime())->getTimestamp() > $expiration_date->getTimestamp()) {
            
            return [
                'status' => false,
                'reason' => "Token is expired.",
            ];

        }else{

            return [
                'status' => true,
                'reason' => "Token is valid.",
            ];

        }

    }

    public static function removeUser(int $idUSer)
    {

        $user = User::find($idUSer);

        if (!$user) {

            return new JsonResponse(
                [
                    'status' => false,
                    'message' => "User not found.",
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );

        }

        $deleted = $user->delete();

        if ($deleted == true) {
            
            return new JsonResponse(
                [
                    'status' => true,
                    'message' => "User deleted.",
                ],
                JsonResponse::HTTP_OK
            );

        }else{

            return new JsonResponse(
                [
                    'status' => false,
                    'message' => "Error on deleting user.",
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );

        }

    }
    
}
