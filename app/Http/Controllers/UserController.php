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
use App\Services\UserValidationService;

class UserController extends Controller
{
    protected $userService;
    protected $uservalidationService;

    public function __construct(UserService $userService, UserValidationService $uservalidationService)
    {
        $this->userService = $userService;
        $this->uservalidationService = $uservalidationService;
    }

    public function store(Request $request): JsonResponse
    {
        $validation = $this->uservalidationService->validateUserData($request->all());

        if ($validation->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Validation error.',
                    'errors' => $validation->errors()
                ],
                401
            );
        }

        try {
            
            $user = $this->userService->createUser($request->all());

            return response()->json(
                [
                    'status' => true,
                    'message' => 'User created!',
                    'id' => $user->id
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
}
