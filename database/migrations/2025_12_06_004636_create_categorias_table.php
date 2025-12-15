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
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string("nombre", 30); //sera un campo obligatorio
            $table->text("detalle")->nullable(); //puede ser nulo. o no es obliatorio
            //softDeletes solo funciona con elocuent y debo de 
            //agrega un campo extra q se llama deleted_at para guardar en BD lo eliminado
            //hacer cambios en su modelo Categoria.php agregando al use SoftDeletes
            $table->softDeletes(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
