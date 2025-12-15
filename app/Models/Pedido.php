<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    //Un pedido tine muchos Productos
    //asi sabemos en un Producto cuantos productos se han vendido
    //ejemplo, un teclado en que pedido se ha vendido y cuantos teclados ya se han vendido 
    public function productos(){
        return $this->belongsToMany(Producto::class)
                            ->withTimestamps()
                            ->withPivot("cantidad");
    }

    //Usuario Pedido --> quien atendió? pertenencia
    public function user(){
        return $this->belongsTo(User::class);
    }

    //Un pedido pertenece a un cliente
    public function cliente(){
        return $this->belongsTo(Cliente::class);
    }
}
