<?php

namespace App\Http\Controllers;

#http
use Illuminate\Http\JsonResponse;

#services
use App\Services\UserService;

#requests
use App\Http\Requests\User\RegisterUserRequest;
use App\Http\Requests\User\MailForgetPasswordUSerRequest;
use App\Http\Requests\User\VerifyTokenUserRequest;
use App\Http\Requests\User\ChangePasswordUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\FindUserRequest;
use App\Http\Requests\User\RemoveUserRequest;

class UserController extends Controller
{

    public function store(RegisterUserRequest $request): JsonResponse
    {
        
        try {

            return UserService::createUser($request->toArray());

        } catch (\Throwable $th) {

            return response()->json(
                [
                    'status' => false,
                    'message' => $th->getMessage(),
                    'trace' => $th->getTrace()
                ],
                500
            );
            
        }
    }

    public function mailForgetPassword(MailForgetPasswordUSerRequest $request) : JsonResponse
    {

        try {
            
            return UserService::handleForgotPassword($request);

        } catch (\Throwable $th) {

            return response()->json(
                [
                    'status' => false,
                    'message' => $th->getMessage()
                ],
                400
            );
            
        }

    }

    public function verifyToken(VerifyTokenUserRequest $request): JsonResponse
    {

        try {
            
            return response()->json(UserService::verifyTokenCondition($request->query->getString('token')), 200);

        } catch (\Throwable $th) {

            return response()->json(
                [
                    'status' => false,
                    'message' => $th->getMessage(),
                    'trace' => $th->getTrace()
                ],
                500
            );
            
        }

    }

    public function changePassword(ChangePasswordUserRequest $request) 
    {

        try {
            
            return UserService::changePasswordUser($request->token, $request->new_password);

        } catch (\Throwable $th) {

            return response()->json(
                [
                    'status' => false,
                    'message' => $th->getMessage(),
                    'trace' => $th->getTrace()
                ],
                500
            );
            
        }

    }

    public function update(UpdateUserRequest $request) 
    {

        try {

            return UserService::updateUser($request->id, $request->toArray());

        } catch (\Throwable $th) {

            return response()->json(
                [
                    'status' => false,
                    'message' => $th->getMessage(),
                    'trace' => $th->getTrace()
                ],
                500
            );
            
        }

    }

    public function find(FindUserRequest $request) : \App\Models\User
    {

        try{

            return UserService::findUser($request->query->getInt('id_user'));

        } catch (\Throwable $th) {

            return response()->json(
                [
                    'status' => false,
                    'message' => $th->getMessage(),
                    'trace' => $th->getTrace()
                ],
                500
            );
            
        }

    }

    public function removeUser(RemoveUserRequest $request)
    {

        try {
    
            return UserService::removeUser($request->id_user);

        } catch (\Throwable $th) {

            return response()->json(
                [
                    'status' => false,
                    'message' => $th->getMessage(),
                    'trace' => $th->getTrace()
                ],
                500
            );
            
        }

    }

}
