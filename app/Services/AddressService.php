<?php

namespace App\Services;

use App\Models\User; 
use Illuminate\Http\JsonResponse;
use App\Models\Address;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AddressService{

    public static function registerAddress(int $id, array $data)
    {

        try {

            $user = User::find($id);

            if (!$user) {

                return new JsonResponse(
                    [
                        'status' => false,
                        'message' => "User not found.",
                    ],
                    JsonResponse::HTTP_BAD_REQUEST
                );

            }
    
            $address = Address::create([
                "street" => $data['street'],
                "city" => $data['city'],
                "state" => $data['state'],
                "zip_code" => $data['zip_code'],
                "country" => $data['country']
            ]);
    
            $user->address_id = $address->id;
    
            $user->save();
    
            return new JsonResponse(
                [
                    'status' => true,
                    'message' => "Your address was added!",
                ],
                JsonResponse::HTTP_OK
            );

        } catch (\Throwable $th) {

            return new JsonResponse(
                [
                    'status' => false,
                    'message' => $th->getMessage(),
                ],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );

        }

    }



}