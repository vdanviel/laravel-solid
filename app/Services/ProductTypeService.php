<?php

namespace App\Services;

#models
use App\Models\ProductType;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class ProductTypeService
{

    public static function register(array $data) : JsonResponse
    {

        $productType = new ProductType();

        $productType->name = $data['name'];
        $productType->description = $data['description'];
        
        $result = $productType->save();

        if ($result == true) {

            return new JsonResponse(
                [
                    'status' => true,
                    'message' => 'Type registered.'
                ],
                JsonResponse::HTTP_OK
            );

        }else{

            return new JsonResponse(
                [
                    'status' => false,
                    'message' => 'Type not registered.'
                ],
                JsonResponse::HTTP_BAD_REQUEST  
            );

        }

    }

    public static function update(int $id, array $data) : JsonResponse
    {

        $productType = ProductType::find($id);

        $productType->name = $data['name'];
        $productType->description = $data['description'];
        
        $result = $productType->save();

        if ($result == true) {

            return new JsonResponse(
                [
                    'status' => true,
                    'message' => 'Type updated.'
                ],
                JsonResponse::HTTP_OK
            );

        }else{

            return new JsonResponse(
                [
                    'status' => false,
                    'message' => 'Type not updated.'
                ],
                JsonResponse::HTTP_BAD_REQUEST  
            );

        }

    }

    public static function index() : Collection
    {

        return ProductType::all();

    }

}
