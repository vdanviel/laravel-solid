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
        Schema::create('product', function(Blueprint $tb) {

            $tb->id();
            $tb->string('name')->nullable();
            $tb->decimal('price',10,2)->nullable();
            $tb->string('company')->nullable();

            $tb->foreignId('type')->nullable();
            $tb->foreign('type')->references('id')->on('product_type');

            $tb->text('desc')->nullable();
            $tb->integer('stock')->nullable();

            $tb->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
