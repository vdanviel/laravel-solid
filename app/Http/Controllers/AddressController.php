<?php

namespace App\Http\Controllers;

#model
use App\Models\Address;

#http
use Illuminate\Http\JsonResponse;

#services
use App\Services\AddressService;

#request
use App\Http\Requests\Address\RegisterAddressRequest;
use App\Http\Requests\Address\SwitchAddressRequest;
use App\Http\Requests\Address\UpdateAddressRequest;
use App\Http\Requests\Address\IndexUserAddressRequest;
use App\Http\Requests\Address\FindAddressRequest;
use App\Http\Requests\Address\RemoveAddressRequest;

class AddressController extends Controller
{

    public function register(RegisterAddressRequest $request) : JsonResponse
    {
        try {
    
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

    public function changeAddress(SwitchAddressRequest $request) : JsonResponse
    {

        try {
    
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

    public function updateAddress(UpdateAddressRequest $request) : JsonResponse
    {

        try {
    
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

    public function indexAddress(IndexUserAddressRequest $request) : \Illuminate\Database\Eloquent\Collection
    {

        try {
    
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

    public function findAddress(FindAddressRequest $request) : Address
    {

        try {
    
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

    public function removeAddress(RemoveAddressRequest $request) : JsonResponse
    {

        try {
    
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
