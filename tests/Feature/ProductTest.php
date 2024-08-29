<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use App\Models\Product;
use App\Models\ProductType;

class ProductTest extends TestCase
{

    //use RefreshDatabase; //RESETA O BANCO DE DADOS

    //php artisan test tests/Feature/ProductTest.php - TO RUN

    //https://laravel.com/docs/11.x/testing - understanding testings
    //https://laravel.com/docs/11.x/http-tests#fluent-json-testing - testings on api JSON
    //https://laravel.com/docs/11.x/http-tests#available-assertions - asserions (maneira de conferir se o retorno bate)

    public function test_index_product(): void
    {

        $loginResponse = $this->postJson('/api/auth', [
            'email' => 'eucreio119@gmail.com',
            'password' => 'password',
        ]);

        $token = $loginResponse->json('token');
    
        ProductType::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->getJson('/api/product/index');

        $response->assertStatus(200)->assertJson(Product::all()->toArray());
    }

    public function test_register_product(): void
    {

        $loginResponse = $this->postJson('/api/auth', [
            'email' => 'eucreio119@gmail.com',
            'password' => 'password',
        ]);
        
        $token = $loginResponse->json('token'); 

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->postJson('/api/product/register', 
            [
                'name' => 'Gamer Earphone',
                'price' => 100.98,
                'company' => 'Unix',
                'type_id' => ProductType::inRandomOrder()->first()->id,
                'desc' => 'Voluptate enim officia eiusmod labore sit voluptate ea pariatur pariatur dolor enim dolore enim et. In quis consequat officia nulla qui cupidatat amet sint. Anim dolore elit dolor velit consectetur. Amet do anim anim nostrud officia non anim velit et fugiat officia.',
                'stock' => 32323
            ]
        );
    
        $response->assertStatus(200)->assertJsonFragment(['status' => true]);//https://laravel.com/docs/11.x/http-tests#assert-json-fragment

    }

    public function test_find_product() : void
    {

        $loginResponse = $this->postJson('/api/auth', [
            'email' => 'eucreio119@gmail.com',
            'password' => 'password',
        ]);
        
        $token = $loginResponse->json('token'); 

        $random_product = Product::inRandomOrder()->first();

        $response = $this->withHeaders(["Authorization" => "Bearer $token"])->getJson('/api/product/find?id=' . $random_product->id);

        $response->assertStatus(200)->assertJson($random_product->toArray());

    }

}
