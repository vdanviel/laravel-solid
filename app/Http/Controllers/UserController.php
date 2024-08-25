<?php

namespace App\Http\Controllers;

#http
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

#services
use App\Services\UserService;
use App\Services\ValidationService;

class UserController extends Controller
{

    public function store(Request $request): JsonResponse
    {

        $valid = ValidationService::dataValidation(
            $request->all(), 
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone_number' => 'nullable|string|max:20',
                'password' => 'required|string|min:8',
            ]
        );

        if($valid instanceof JsonResponse) return $valid;
        
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

    public function mailForgetPassword(Request $request) : JsonResponse
    {

        $valid = ValidationService::dataValidation($request->all(), 
            [
                'email' => 'required|email',
            ]
        );

        if($valid instanceof JsonResponse) return $valid;

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

    public function verifyToken(Request $request): JsonResponse
    {

        $valid = ValidationService::dataValidation($request->query->all(), ['token' => 'required|string']);

        if($valid instanceof JsonResponse) return $valid;

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

    public function changePassword(Request $request) 
    {
        
        $valid = ValidationService::dataValidation($request->all(), ['token' => 'required|string', 'new_password' => 'required|string']);

        if($valid instanceof JsonResponse) return $valid;

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

    public function update(Request $request) 
    {
        
        $valid = ValidationService::dataValidation($request->all(), ['id' => 'required|integer', 'name' => 'required|string', 'email' => 'required|string', 'phone_number' => 'required|string']);

        if($valid instanceof JsonResponse) return $valid;

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

    public function find(Request $request) : \App\Models\User
    {

        $valid = ValidationService::dataValidation($request->query->all(), ['id_user' => 'required|integer']);

        if($valid instanceof JsonResponse) return $valid;

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

    public function removeUser(Request $request)
    {

        try {
            
            $valid = ValidationService::dataValidation($request->all(), ['id_user' => 'required|integer']);

            if($valid instanceof JsonResponse) return $valid;
    
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
