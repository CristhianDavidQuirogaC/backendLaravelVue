<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    //Relacioinamos.. este producto pertenece a una categoría
    public function categoria()
    {
        //este producto pertenece a una categoria
        return $this->belongsTo(Categoria::class); 
    }

    //Relacionamos la clase producto tiene muchos pedidos
    public function pedidos()
    {
        //la tabla relacion q trabaje con los dos datos extras created y updated
        //que empice a observar esos campos y tambien la columna cantidad
        return $this->belongsToMany(Pedido::class)
                        ->withTimestamps()
                        ->wherePivot("cantidad");
    }
}
