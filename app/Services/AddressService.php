<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

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

            // Instancia o endereço sem salvar diretamente no banco de dados
            $address = new Address();
            $address->street = $data['street'];
            $address->city = $data['city'];
            $address->state = $data['state'];
            $address->zip_code = $data['zip_code'];
            $address->user_id = $user->id;  // Associa o endereço ao usuário
            
            Address::where('user_id', $id)->update(['active' => false]);

            $address->active = true;

            // Salva o endereço no banco de dados
            $address->save();
    
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

    public static function switchUserAddress(int $idUser, int $idAddress) : JsonResponse
    {

        $user = User::find($idUser);

        if (!$user) {

            return new JsonResponse(
                [
                    'status' => false,
                    'message' => "User not found.",
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );

        }

        //antes de setar o ativo atual, desativa todos os outros para ter somente um em true..
        Address::where('user_id', $user->id)->update(['active' => false]);

        $address = Address::find($idAddress);

        if (!$address) {

            return new JsonResponse(
                [
                    'status' => false,
                    'message' => "Address not found.",
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );

        }

        $address->active = true;

        $result = $address->save();

        if ($result == true) {
            
            return new JsonResponse(
                [
                    'status' => true,
                    'message' => "Address swiched.",
                ],
                JsonResponse::HTTP_OK
            );

        }else{

            return new JsonResponse(
                [
                    'status' => false,
                    'message' => "Error on swithing address.",
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );

        }

    }

    public static function updateAddress(int $idAddress, array $data): JsonResponse
    {

        $address = Address::find($idAddress);

        if (!$address) {

            return new JsonResponse(
                [
                    'status' => false,
                    'message' => "Address not found.",
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );

        }

        $address->street = $data['street'];
        $address->city = $data['city'];
        $address->state = $data['state'];
        $address->zip_code = $data['zip_code'];
        $address->country = $data['country'];

        $result = $address->save();

        if ($result == true) {
            
            return new JsonResponse(
                [
                    'status' => true,
                    'message' => "Address updated.",
                ],
                JsonResponse::HTTP_OK
            );

        }else{

            return new JsonResponse(
                [
                    'status' => false,
                    'message' => "Error on updating address.",
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );

        }

    }

    public static function indexUserAddress(int $idUser): Collection 
    {

        //antes de setar o ativo atual, desativa todos os outros para ter somente um em true..
        $addresses = Address::where('user_id', $idUser)->get();

       return $addresses;

    }

    public static function findAddress(int $idAddress): Address
    {
        
        $address = Address::find($idAddress);

        return $address;

    }

    public static function deleteAddress(int $idAddress): JsonResponse
    {
        
        $address = Address::find($idAddress);

        if (!$address) {

            return new JsonResponse(
                [
                    'status' => false,
                    'message' => "Address not found.",
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );

        }

        $deleted = $address->delete();

        if ($deleted == true) {
            
            return new JsonResponse(
                [
                    'status' => true,
                    'message' => "Address deleted.",
                ],
                JsonResponse::HTTP_OK
            );

        }else{

            return new JsonResponse(
                [
                    'status' => false,
                    'message' => "Error on deleting address.",
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );

        }

    }

}