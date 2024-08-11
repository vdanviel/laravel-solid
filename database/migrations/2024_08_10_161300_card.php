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
        Schema::create('card', function(Blueprint $tb){

            $tb->id();

            $tb->foreignId('user_id')->nullable();
            $tb->foreign('user_id')->references('id')->on('users');

            $tb->decimal('amount', 10, 2)->nullable();

            $tb->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card');
    }
};
