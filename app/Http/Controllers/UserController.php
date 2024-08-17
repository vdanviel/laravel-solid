<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use \Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Throwable;

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
            
            $user = UserService::createUser($request->all());

            return response()->json(
                [
                    'status' => true,
                    'message' => 'User created!'
                ],
                200
            );

        } catch (Throwable $th) {

            return response()->json(
                [
                    'status' => false,
                    'message' => $th->getMessage()
                ],
                400
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

        } catch (Throwable $th) {

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

        } catch (Throwable $th) {

            return response()->json(
                [
                    'status' => false,
                    'message' => $th->getMessage()
                ],
                400
            );
            
        }

    }

    public function changePassword(Request $request) 
    {
        
        $valid = ValidationService::dataValidation($request->all(), ['token' => 'required|string', 'new_password' => 'required|string']);

        if($valid instanceof JsonResponse) return $valid;

        try {
            
            return UserService::changePasswordUser($request->token, $request->new_password);

        } catch (Throwable $th) {

            return response()->json(
                [
                    'status' => false,
                    'message' => $th->getMessage()
                ],
                400
            );
            
        }

    }

}
