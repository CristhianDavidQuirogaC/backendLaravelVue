<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; //para registros de eliminacion

class Categoria extends Model
{
    use SoftDeletes, HasFactory;

    //Con esta funcion productos obtenemos todos los productos de esta Categoria
    //relación de uno a muchos
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
