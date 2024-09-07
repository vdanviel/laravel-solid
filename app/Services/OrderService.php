<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Card;
use App\Models\CardItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class OrderService {

    private $apiKey;

    public function __construct(){

        $this->apiKey = config('stripe.secret_key');

    }

    public static function createSession(int $card_id): JsonResponse
    {
        
        //procurando items do carrinho
        $cardItems = CardItem::where('card_id', $card_id)->get(['product_id', 'quantity']);
        
        //separando os ids dos produtos dos itens e as quantidades de cada item
        $productIds = $cardItems->pluck('product_id')->toArray();
        $quantities = $cardItems->pluck('quantity')->toArray();

        //se não houver itens ent anula..
        if (!$cardItems) {
            return new JsonResponse(
                [
                    'status' => false,
                    'message' => "No items in card."
                ],
                JsonResponse::HTTP_UNAUTHORIZED
            );
        }
        
        //criando pedido..
        $order = new Order;
        $order->card_id = $card_id;
        $order->status = 'pending';
        
        //procurando o endereço do usuario que fez o pedido..
        $card = Card::find($card_id);

        if (!$card) {
            return new JsonResponse(
                [
                    'status' => false,
                    'message' => "Card doesn't exists."
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        $address = Address::where('user_id', $card->user_id)->where('active', true)->first();
        
        //se ele n tem endereço ativo ou n tem endereços ele n pode fazer pedidio..
        if (!$address) {
            return new JsonResponse(
                [
                    'status' => false,
                    'message' => "Active address not found for user."
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }
    
        $order->address_id = $address->id;
    
        if (!$order->save()) {
            return new JsonResponse(
                [
                    'status' => false,
                    'message' => "Order not saved."
                ],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        

        //listando os dados dos produtos dos items..
        $products = Product::whereIn('id', $productIds)->get();
        
        //criando a estrutura de linha de itens do stripe...
        $lineItems = [];
        foreach ($products as $key => $product) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'brl',
                    'product_data' => [//https://docs.stripe.com/api/invoice-line-item/bulk#bulk_add_lines-lines-price_data-product_data
                        'name' => $product->name,
                        'description' => $product->desc,
                        'images' => ['https://upload.wikimedia.org/wikipedia/commons/thumb/3/3d/LaravelLogo.png/800px-LaravelLogo.png']
                    ],
                    'unit_amount' => $product->price * 100,
                ],
                'quantity' => $quantities[$key],
            ];
        }
    
        //stripe..
        try {

            //recupera a chave api
            \Stripe\Stripe::setApiKey((new OrderService())->apiKey);

            //criando a session stripe..
            $checkoutSession = \Stripe\Checkout\Session::create([
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => url('/api/order/success'),
                'cancel_url' => url('/api/order/cancel')
            ]);

        } catch (\Exception $e) {

            return new JsonResponse(
                [
                    'status' => false,
                    'message' => "Failed to create Stripe checkout session: " . $e->getMessage()
                ],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
            
        }
        
        // terminando de salvar o pedido com o tipo de pedido receuprado pela api stripe ("payment_method")
        $order->payment_method = $checkoutSession->payment_method_types[0] ?? null;
        $order->stripe_session_id = $checkoutSession->id ?? null;
        
        $result = $order->save();

        //se n for anula..
        if (!$result) {
            return new JsonResponse(
                [
                    'status' => false,
                    'message' => "Order not saved. Payment method is not updated."
                ],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        
        //deletar carrinho se tudo der certo
        //$cardItems->delete();
        //Card::find($card_id)->delete();

        //retorna a URL de checkout para o frontend..
        return response()->json([
            'status' => true,
            'checkout_url' => $checkoutSession->url
        ]);

    }

}