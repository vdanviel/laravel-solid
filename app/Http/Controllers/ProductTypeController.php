<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Product\Type\ProductTypeRegisterRequest;
use App\Http\Requests\Product\Type\ProductTypeUpdateRequest;
use App\Services\ProductTypeService;

class ProductTypeController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        try {
            
            return ProductTypeService::index();

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

    public function store(ProductTypeRegisterRequest $request)
    {
        
        try {
            
            return ProductTypeService::register($request->toArray());

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

    public function update(ProductTypeUpdateRequest $request)
    {
        try {
            
            return ProductTypeService::update($request->id_type, $request->toArray());

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
