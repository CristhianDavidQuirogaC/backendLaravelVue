<?php

namespace App\Http\Controllers;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //Aqui vamos a buscar capturando el valor de búsqueda $q
        $q = $request->q;
        if($q){
            //si $q = ci_nit con first() va a capturar el único objeto
            //$cliente = Cliente::where("ci_nit", "=", $q)->fist();
            //con el filtro get() encontrará sugerencias q tiene algo de lo que se esta buscando
            //first() devuelve un unico --- get() devuelve un arreglo
            //Cliente::where("ci_nit", "=", $q)->get();
            $cliente = Cliente::orWhere("ci-nit", "like", "%".$q."%")
                                ->orWhere("nombre_completo", "like", "%".$q."%")
                                ->first();
            
            //si el $q no es identico al ci_nit del cliente. el objeto será Nulo
        }

        //si no lo encuentra retornará un objeto vacio o null
        return response()->json($cliente, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Para guardar un cliente
        $clie = new Cliente();
        $clie->nombre_completo = $request->nombre_completo;
        $clie->telefono = $request->telefono;
        $clie->correo = $request->correo;
        $clie->ci_nit = $request->ci_nit;

        $clie->save();
        return response()->json($clie);
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
