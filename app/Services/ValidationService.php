<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as Validation;

class ValidationService {
    
    public static function dataValidation(array $data, array $rules): JsonResponse | Validation
    {
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return new JsonResponse(
                [
                    'status' => false,
                    'message' => 'Validation error.',
                    'errors' => $validator->errors(),
                ],
                422
            );
        }

        return $validator;
    }
}
