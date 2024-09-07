<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

#requests
use App\Http\Requests\Order\OrderCreateRequest;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

class OrderController
{

    public function create(OrderCreateRequest $request)
    {
        
        try {

            return OrderService::createSession($request->card_id);

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

    public function success(){

        try {

            return new JsonResponse(
                [
                    'status' => 'success',
                    'message' => "The payment was sucessfully done."
                ]
            );

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

    public function cancel(){

        try {

            return new JsonResponse(
                [
                    'status' => 'cancel',
                    'message' => "The payment was sucessfully cancelled."
                ]
            );

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
