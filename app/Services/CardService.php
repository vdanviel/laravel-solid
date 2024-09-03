<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use App\Models\Card;
use App\Models\CardItem;
use Illuminate\Database\Eloquent\Casts\Json;

class CardService {

    public static function register(array $data) : JsonResponse
    { 

        $card = new Card;

        $card->user_id = $data['id_user'];
        $card->amount = $data['amount'];

        $result = $card->save();

        if ($result == true) {
            
            return new JsonResponse(
                [
                    'status' => true,
                    'message' => 'Card was created!',
                    JsonResponse::HTTP_OK
                ]
            );

        }else{

            return new JsonResponse(
                [
                    'status' => false,
                    'message' => 'Card was not created.',
                    JsonResponse::HTTP_INTERNAL_SERVER_ERROR
                ]
            );

        }

    }

    public static function delete(int $id) : JsonResponse
    { 

        $result = Card::find($id)->delete();

        if ($result == true) {
            
            return new JsonResponse(
                [
                    'status' => true,
                    'message' => 'Card was deleted!',
                    JsonResponse::HTTP_OK
                ]
            );

        }else{

            return new JsonResponse(
                [
                    'status' => false,
                    'message' => 'Card was not deleted.',
                    JsonResponse::HTTP_INTERNAL_SERVER_ERROR
                ]
            );

        }

    }

    public static function all_user_items(int $idUser) : JsonResponse
    { 

        $cardId = Card::where('user_id', $idUser)->limit(1)->first()->id;

        $items = CardItem::where('card_id', $cardId)->get();

        return new JsonResponse($items, JsonResponse::HTTP_OK);

    }

}