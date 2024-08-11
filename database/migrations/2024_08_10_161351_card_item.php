<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('card_item', function(Blueprint $tb){

            $tb->id(); 

            $tb->foreignId('product_id')->nullable();
            $tb->foreign('product_id')->references('id')->on('product');

            $tb->foreignId('card_id')->nullable();
            $tb->foreign('card_id')->references('id')->on('card');

            $tb->decimal('amount', 10,2)->nullable();

            $tb->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
