<?php

namespace Tests\Feature;

use App\Models\Card;
use App\Models\CardItem;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\User;
use App\Services\CardService;
use App\Services\ProductService;
use App\Services\ProductTypeService;
use Tests\TestCase;

class CardItemTest extends TestCase
{

    /**
     * A basic feature test example.
     */
    public function test_add_item_on_card(): void
    {

        $responseToken = $this->postJson('/api/auth', 
            [
                'email' => 'eucreio119@gmail.com',
                'password' => 'password'
            ]
        );

        $token = $responseToken->json('token');

        ProductType::create(['name' => 'word', 'description' => 'Sunt eiusmod consequat minim non consequat proident anim.']);
        Product::create(['name' => 'test', 'price' => 111.21, 'company' => 'axy', 'type_id' => ProductType::inRandomOrder()->first()->id, 'desc' => 'Occaecat ad cillum amet consequat sint nulla ea veniam sint.', 'stock' => 11]);
        
        Card::create(['user_id' => User::inRandomOrder()->first()->id, 'amount' => 0.00]);

        $response = $this->withHeaders(["Authorization" => "Bearer $token"])->postJson('/api/item/add', [
            'product_id' => Product::inRandomOrder()->first()->id,
            'card_id'=> Card::inRandomOrder()->first()->id,
            'qnt' => 5
        ]);

        $response->assertJsonFragment(['status' => true]);
    }

    public function test_remove_item_from_card() : void
    {

        $responseToken = $this->postJson('/api/auth', 
            [
                'email' => 'eucreio119@gmail.com',
                'password' => 'password'
            ]
        );

        $token = $responseToken->json('token');

        $response = $this->withHeaders(["Authorization" => "Bearer $token"])->deleteJson('/api/item/remove', [
            'item_card_id' => CardItem::inRandomOrder()->first()->id
        ]);

        $response->assertJsonFragment(['status' => true]);

    }

    public function test_alter_item_quantity() : void
    {

        $responseToken = $this->postJson('/api/auth', 
            [
                'email' => 'eucreio119@gmail.com',
                'password' => 'password'
            ]
        );

        $token = $responseToken->json('token');

        $response = $this->withHeaders(["Authorization" => "Bearer $token"])->patchJson('/api/item/alter/qnt', [
            'item_card_id' => CardItem::inRandomOrder()->first()->id,
            'qnt' => 2
        ]);

        $response->assertJsonFragment(['status' => true]);

    }

}
