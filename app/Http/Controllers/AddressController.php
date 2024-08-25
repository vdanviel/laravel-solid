<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\AddressService;
use App\Services\ValidationService;

class AddressController extends Controller
{

    public function register(Request $request)
    {
        try {
            
            $valid = ValidationService::dataValidation($request->all(), ['id_user' => 'required|integer', 'street' => 'required|string', 'city' => 'required|string', 'state' => 'required|string', 'zip_code' => 'required|string', 'country' => 'required|string']);

            if($valid instanceof JsonResponse) return $valid;
    
            return AddressService::registerAddress($request->id_user, $request->toArray());

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

    public function changeAddress(Request $request)
    {

        try {
            
            $valid = ValidationService::dataValidation($request->all(), ['id_user' => 'required|integer','id_address' => 'required|integer']);

            if($valid instanceof JsonResponse) return $valid;
    
            return AddressService::switchUserAddress($request->id_user, $request->id_address);

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

    public function updateAddress(Request $request)
    {

        try {
            
            $valid = ValidationService::dataValidation($request->all(), ['id_address' => 'required|integer', 'street' => 'string', 'city' => 'string', 'state' => 'string', 'zip_code' => 'string', 'country' => 'string']);

            if($valid instanceof JsonResponse) return $valid;
    
            return AddressService::updateAddress($request->id_address, $request->toArray());

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

    public function indexAddress(Request $request)
    {

        try {
            
            $valid = ValidationService::dataValidation($request->query->all(), ['id_user' => 'required|integer']);

            if($valid instanceof JsonResponse) return $valid;
    
            return AddressService::indexUserAddress($request->query->getInt('id_user'));

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

    public function findAddress(Request $request)
    {

        try {
            
            $valid = ValidationService::dataValidation($request->query->all(), ['id_address' => 'required|integer']);

            if($valid instanceof JsonResponse) return $valid;
    
            return AddressService::findAddress(intval($request->query->getString('id_address')));

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

    public function removeAddress(Request $request)
    {

        try {
            
            $valid = ValidationService::dataValidation($request->all(), ['id_address' => 'required|integer']);

            if($valid instanceof JsonResponse) return $valid;
    
            return AddressService::deleteAddress($request->id_address);

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
