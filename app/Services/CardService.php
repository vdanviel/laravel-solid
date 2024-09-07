<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use App\Models\Card;
use App\Models\CardItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Json;

class CardService {

    public static function register(array $data): JsonResponse
    { 
        // Verifica se o usuário existe
        $user = User::find($data['id_user']);
    
        if (!$user) {
            return new JsonResponse(
                [
                    'status' => false,
                    'message' => "User not found.",
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }
    
        // Verifica se o usuário já possui um carrinho
        $existingCard = Card::where('user_id', $data['id_user'])->first();
        
        if ($existingCard) {
            return new JsonResponse(
                [
                    'status' => false,
                    'message' => 'User already has a cart.',
                    'id' => $existingCard->id
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }
    
        // Cria um novo carrinho para o usuário
        $card = new Card;
        $card->user_id = $data['id_user'];
        $card->amount = 0.00;
    
        $result = $card->save();
    
        if ($result) {
            return new JsonResponse(
                [
                    'status' => true,
                    'message' => 'Card was created!',
                    'id' => $card->id
                ],
                JsonResponse::HTTP_OK
            );
        } else {
            return new JsonResponse(
                [
                    'status' => false,
                    'message' => 'Card was not created.',
                ],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
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