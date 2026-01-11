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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->dateTime("fecha_pedido"); //lo capturamos del servidor
            $table->integer("estado")->default(1);
            $table->string("cod_factura")->nullable();
            //el vendedor (user_id) atenderá al pedido
            $table->bigInteger("user_id")->unsigned(); //usuario actual
            //que cliente va a comprar?
            $table->bigInteger("cliente_id")->unsigned();
            //relacion pedido con cliente y con usuario
            $table->foreign("user_id")->references("id")->on("users");
            $table->foreign("cliente_id")->references("id")->on("clientes");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
