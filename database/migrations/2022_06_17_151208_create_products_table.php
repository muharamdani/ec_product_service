<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->string('code')->nullable();
            $table->string('name');
            $table->double('price');
            $table->integer('stock');
            $table->integer('min_order');
            $table->boolean('is_new');
            $table->boolean('is_active')->default(false);
            $table->integer('weight');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
