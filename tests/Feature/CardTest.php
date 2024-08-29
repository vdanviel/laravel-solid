<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Card;
use Tests\TestCase;

class CardTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_create_card(): void
    {

        $responseToken = $this->postJson('/user/login', 
            [
                'email' => 'eucreio119@gmail.com',
                'password' => 'password'
            ]
        );

        $token = $responseToken->json('token');

        $response = $this->withHeader("Authorization", "Bearer $token")->postJson('/api/card/register');

        $response->assertStatus(200)->assertJsonFragment(['status' => true]);
    }

    public function test_delete_card(): void
    {

        $responseToken = $this->postJson('/user/login', 
            [
                'email' => 'eucreio119@gmail.com',
                'password' => 'password'
            ]
        );

        $token = $responseToken->json('token');

        $randomCard = Card::inRandomOrder()->first();

        $response = $this->withHeader("Authorization", "Bearer $token")->postJson('/api/card/delete?id=' . $randomCard->id);

        $response->assertStatus(200)->assertJsonFragment(['status' => true]);
    }

}
