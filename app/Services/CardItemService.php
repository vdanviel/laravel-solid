<?php

namespace App\Services;

use App\Models\Card;
use Illuminate\Http\JsonResponse;
use App\Models\CardItem;
use App\Models\Product;

class CardItemService {

    public static function register(array $data): JsonResponse
    {
        $product = Product::find($data['product_id']);
    
        if (!$product) {
            return new JsonResponse(
                [
                    'status' => false,
                    'message' => "Product not found.",
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }
    
        $card = Card::find($data['card_id']);
    
        if (!$card) {
            return new JsonResponse(
                [
                    'status' => false,
                    'message' => "Card not found.",
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }
    
        //encontrar o valor do produto
        $productValue = floatval($product->price);
    
        //calcular o valor total do item (preço do produto vezes a quantidade comprada)
        $itemAmountValue = floatval($data['qnt']) * $productValue;
    
        //verificar se o item já está presente no carrinho
        $existingCardItem = CardItem::where('product_id', $data['product_id'])
            ->where('card_id', $data['card_id'])
            ->first();
    
        if ($existingCardItem) {
            //atualizar a quantidade e o valor do item existente
            $existingCardItem->quantity += floatval($data['qnt']);
            $existingCardItem->amount += $itemAmountValue;
            $result = $existingCardItem->save();
        } else {
            //criar um novo registro de item no carrinho
            $cardItem = new CardItem();
            $cardItem->product_id = $data['product_id'];
            $cardItem->card_id = $data['card_id'];
            $cardItem->quantity = floatval($data['qnt']);
            $cardItem->amount = $itemAmountValue;
            $result = $cardItem->save();
        }
    
        //atualizar o valor total do carrinho
        $card->amount += $itemAmountValue;
        $card->save();
    
        if ($result) {
            return new JsonResponse(
                [
                    'status' => true,
                    'message' => 'Item was added or updated in the card.',
                ],
                JsonResponse::HTTP_OK
            );
        } else {
            return new JsonResponse(
                [
                    'status' => false,
                    'message' => 'Item was not added or updated in the card.',
                ],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public static function update_quantity(int $id, int $qnt) : JsonResponse
    { 

        $item = CardItem::find($id);

        $item->quantity = $qnt;

        $result = $item->save();

        if ($result == true) {
            
            return new JsonResponse(
                [
                    'status' => true,
                    'message' => 'Item quantity is updated!',
                    JsonResponse::HTTP_OK
                ]
            );

        }else{

            return new JsonResponse(
                [
                    'status' => false,
                    'message' => 'Item quantity is not updated!',
                    JsonResponse::HTTP_INTERNAL_SERVER_ERROR
                ]
            );

        }

    }

    public static function delete(int $id) : JsonResponse
    { 

        $result = CardItem::find($id)->delete();

        if ($result == true) {
            
            return new JsonResponse(
                [
                    'status' => true,
                    'message' => 'Item removed from card!',
                    JsonResponse::HTTP_OK
                ]
            );

        }else{

            return new JsonResponse(
                [
                    'status' => false,
                    'message' => 'Item not removed from card.',
                    JsonResponse::HTTP_INTERNAL_SERVER_ERROR
                ]
            );

        }

    }

}