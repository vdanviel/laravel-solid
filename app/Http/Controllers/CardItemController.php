<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Card;
use App\Services\CardItemService;

#requests
use App\Http\Requests\Card\Item\CardItemCreateRequest;
use App\Http\Requests\Card\Item\CardItemRemoveRequest;
use App\Http\Requests\Card\Item\CardItemUpdateQuantityRequest;


class CardItemController extends Controller
{

    public function create(CardItemCreateRequest $request)
    {
        try {
            
            return CardItemService::register($request->toArray());

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

    public function remove(CardItemRemoveRequest $request)
    {
        
        try {
            
            return CardItemService::delete($request->item_card_id);

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

    public function alterItemQuantity(CardItemUpdateQuantityRequest $request)
    {

        try {
            
            return CardItemService::update_quantity($request->item_card_id, $request->qnt);

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
