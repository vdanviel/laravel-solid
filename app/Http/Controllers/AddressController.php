<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\JWTService;
use App\Services\AddressService;
use App\Services\ValidationService;

class AddressController extends Controller
{

    public function register(Request $request)
    {
        try {
            
            $valid = ValidationService::dataValidation($request->all(), ['id' => 'required|integer', 'street' => 'required|string', 'city' => 'required|string', 'state' => 'required|string', 'zip_code' => 'required|string', 'country' => 'required|string']);

            if($valid instanceof JsonResponse) return $valid;
    
            return AddressService::registerAddress($request->id, $request->toArray());

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
