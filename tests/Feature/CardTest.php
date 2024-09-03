<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Card;
use App\Models\User;
use Tests\TestCase;

class CardTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_create_card(): void
    {

        $responseToken = $this->postJson('/api/auth', 
            [
                'email' => 'eucreio119@gmail.com',
                'password' => 'password'
            ]
        );

        $token = $responseToken->json('token');

        $response = $this->withHeaders(["Authorization" => "Bearer $token"])->postJson('/api/card/register', 
            [
            'id_user' => 1,
            'amount' => '0.00'
            ]
        );

        $response->assertJsonFragment(['status' => true]);
    }

    public function test_delete_card(): void
    {

        $responseToken = $this->postJson('/api/auth', 
            [
                'email' => 'eucreio119@gmail.com',
                'password' => 'password'
            ]
        );

        $token = $responseToken->json('token');

        $randomCardId = Card::inRandomOrder()->first()->id;

        $response = $this->withHeaders(["Authorization" => "Bearer $token"])->deleteJson('/api/card/delete', [
            'card_id' => $randomCardId
        ]);

        $response->assertJsonFragment(['status' => true]);
    }

    public function test_display_user_items(): void
    {

        $responseToken = $this->postJson('/api/auth', 
            [
                'email' => 'eucreio119@gmail.com',
                'password' => 'password'
            ]
        );

        $token = $responseToken->json('token');

        $id = User::inRandomOrder()->first()->id;

        $response = $this->withHeaders(["Authorization" => "Bearer $token"])->getJson('/api/card/user?user_id=' . $id);

        $response->assertJsonIsArray();;
    }

}
