<?php

namespace App\Services;

#models
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductService
{

    public static function register(array $data) : JsonResponse
    {

        $product = new Product();

        $product->name = $data['name'];
        $product->price = $data['price'];
        $product->company = $data['company'];
        $product->type_id = $data['type_id'];
        $product->desc = $data['desc'];
        $product->stock = $data['stock'];
        
        $result = $product->save();

        if ($result == true) {

            return new JsonResponse(
                [
                    'status' => true,
                    'message' => 'Product registered.'
                ],
                JsonResponse::HTTP_OK
            );

        }else{

            return new JsonResponse(
                [
                    'status' => false,
                    'message' => 'Product not registered.'
                ],
                JsonResponse::HTTP_BAD_REQUEST  
            );

        }

    }

    public static function update(int $idProduct, array $data) : JsonResponse
    {

        $product = Product::find($idProduct);

        $product->name = $data['name'];
        $product->price = $data['price'];
        $product->company = $data['company'];
        $product->type_id = $data['type_id'];
        $product->desc = $data['desc'];
        $product->stock = $data['stock'];
        
        $result = $product->save();

        if ($result == true) {

            return new JsonResponse(
                [
                    'status' => true,
                    'message' => 'Product updated.'
                ],
                JsonResponse::HTTP_OK
            );

        }else{

            return new JsonResponse(
                [
                    'status' => false,
                    'message' => 'Product not updated.'
                ],
                JsonResponse::HTTP_BAD_REQUEST  
            );

        }

    }

}
