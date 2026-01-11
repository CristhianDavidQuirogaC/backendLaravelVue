<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Devolverá la lista, importamos Pedido y lo paginamos
        //pedimos q devulva tambien el Cliente y sus productos q compro en ese pedido
        $pedidos = Pedido::with('Cliente', 'productos')->paginate(10);
        //retornamos los $pedidos
        return response()->json($pedidos, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //para guardar un producto completo debemos de saber TRANSACTIONS
        //validamos los datos que estan llegando
        $request->validate([
            //primero validamos al cliente, si no hay cliente,, no hay pedido
            "cliente_id" => "required",
        ]);
        //ahora validamos al usuario actual q esta manejando la sesion o vendedor
        $user = Auth::user();
        $fecha_pedido = date("Y-m-d H:i:s");
        //empezamos importando al clase DB
        DB::beginTransaction();

        try {
            //GUARDAR PEDIDO
            $pedido = new Pedido(); //creamos la variables
            $pedido->user_id = $user->id; //guardamos el id del ususario actual
            $pedido->cliente_id = $request->cliente_id;
            $pedido->fecha_pedido = $fecha_pedido;
            $pedido->save();

            // ASIGNAR PRODUCTOS AL PEDIDO
            //capturamos los productos q llegan en el $request
            $productos = $request->productos; //$productos es un array 
            foreach($productos as $prod){
                //asignamos uno por uno Muchos a muchos ATTACH q es como un push
                //en $pedido se le esta asignado en la tabla relacion productos el id_producto con su cantidad
                $pedido->productos()->attach($prod['id_producto'], ["cantidad" => $prod['cantidad']]);
            }

            //ACTUALIZAR ESTADO DEL PEDIDO
            $pedido->estado = 2;
            $pedido->update();

            //se inserta o modifica las tablas
            // all good
            //si cumple todo el porceso de arriba entonces realiza los cambios
            DB::commit();
            // RETORNAR
            return response()->json(["mensaje" => "Pedido Registrado"], 200);
            
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong y luego hace un rollback revierte todos los cambios 
            return response()->json(["mensaje" => "Error al  Registrar", "error" =>$e->getMessage()], 422);

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
