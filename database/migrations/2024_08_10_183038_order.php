<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('order', function(Blueprint $tb){

            $tb->id();

            $tb->foreignId('card_id')->nullable();
            $tb->foreign('card_id')->references('id')->on('card');

            $tb->enum('status', ['done', 'pending', 'cancelled', 'unpaid']);

            $tb->foreignId('address_id')->nullable();
            $tb->foreign('address_id')->references('id')->on('address');

            $tb->string('payment_method')->nullable();

            $tb->string('stripe_session_id')->nullable();

            $tb->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order');
    }
    
};
