<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// KARENA WAKTU KURANG MENCUKUPI, JADI TABLE NYA SAJA
// PRODUCT BAIKNYA PUNYA VARIANT, EX. SIZE(S, M, L, XL)
// SEMENTARA VARIANT DITULIS DI DESKRIPSI PRODUK KETIKA CREATE PRODUCT
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained('products');
            $table->string('name');
            $table->string('type');
            $table->integer('stock')->default(0);
            $table->double('price');
            $table->integer('weight');
            $table->timestamps();
            $table->index('price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_categories');
    }
};
