<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Card;
use App\Models\CardItem;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\User;
use App\Services\CardItemService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_create_order(): void
    {
        $jwtToken = $this->postJson('/api/auth', [
            'email' => 'eucreio119@gmail.com',
            'password' => 'password'
        ]);

        $token = $jwtToken->json('token');

        ProductType::create(['name' => 'word', 'description' => 'Sunt eiusmod consequat minim non consequat proident anim.']);
        Product::create(['name' => 'test', 'price' => 111.21, 'company' => 'axy', 'type_id' => ProductType::inRandomOrder()->first()->id, 'desc' => 'Occaecat ad cillum amet consequat sint nulla ea veniam sint.', 'stock' => 11]);
        
        $card = Card::create(['user_id' => User::inRandomOrder()->first()->id, 'amount' => 0.00]);

        CardItemService::register(['product_id' => Product::inRandomOrder()->first()->id, 'card_id'=> $card->id, 'qnt' => 4]);

        $response = $this->withHeaders(["Authorization" => "Bearer $token"])->postJson('/api/order/register', [
            'card_id' => $card->id,
        ]);

        $response->assertJsonFragment(['status' => true]);
    }

    public function test_success_order(): void
    {
        $jwtToken = $this->postJson('/api/auth', [
            'email' => 'eucreio119@gmail.com',
            'password' => 'password'
        ]);

        $token = $jwtToken->json('token');

        $response = $this->withHeaders(["Authorization" => "Bearer $token"])->patchJson('/api/order/success', [
            'order_id' => 1
        ]);

        $response->assertJsonFragment(['status' => true]);
    }

    public function test_cancel_order(): void
    {
        $jwtToken = $this->postJson('/api/auth', [
            'email' => 'eucreio119@gmail.com',
            'password' => 'password'
        ]);

        $token = $jwtToken->json('token');

        $response = $this->withHeaders(["Authorization" => "Bearer $token"])->patchJson('/api/order/cancel', [
            'order_id' => 1
        ]);

        $response->assertJsonFragment(['status' => true]);
    }

}
