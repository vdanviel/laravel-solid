<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CardItemTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_add_item_on_card(): void
    {
        $response = $this->postJson('/');
    }
}
