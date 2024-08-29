<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;

#requests
use App\Http\Requests\Product\RegisterProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Requests\Product\FindProductRequest;

class ProductController
{

    public function index()
    {
        try {
            
            return ProductService::index();

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


    public function store(RegisterProductRequest $request)
    {

        try {
            
            return ProductService::register($request->toArray());

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

    public function show(FindProductRequest $request)
    {
        
        try {
            
            return ProductService::find($request->query->getInt('id'));

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

    public function update(UpdateProductRequest $request)
    {
        try {
            
            return ProductService::update($request->id, $request->toArray());

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
