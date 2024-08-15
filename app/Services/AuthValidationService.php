<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as Validation;

class AuthValidationService {
    
    public static function authDataValidation(array $data) : Validation | JsonResponse
    {

        $validator = Validator::make(
            $data,
            [
                'email' => 'required|email',
                'password' => 'required'
            ]
        );

        if ($validator->fails()) {

            return response()->json([
                'status' => false,
                'message' => 'Validation error.',
                'errors' => $validator->errors(),
            ], 422);

        }else{

            return $validator;

        }

    }

}
