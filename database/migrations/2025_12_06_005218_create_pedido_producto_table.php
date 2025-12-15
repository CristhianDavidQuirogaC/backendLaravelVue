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
        Schema::create('pedido_producto', function (Blueprint $table) {
            $table->id();
            $table->integer("cantidad")->default(1);
            $table->bigInteger("pedido_id")->unsigned();
            $table->bigInteger("producto_id")->unsigned(); //que producto en q pedido se almacena?
            //relacion NxM muchos a muchos
            $table->foreign("pedido_id")->references("id")->on("pedidos");
            $table->foreign("producto_id")->references("id")->on("productos");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedido_producto');
    }
};
