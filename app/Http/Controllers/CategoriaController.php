<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria; //importamos a su modelo

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //preguntamo si esta tabla categoria tendrá muchas categorias + de 100
        //tendríamos que paginar
        //lo de abajo solo devuelve el id y el nombre
        //$categorias = Categoria::select("id", "nombre")->get(); 

        //lo de abajo solo devuelve todos los datos de categoría
        $categorias = Categoria::orderBy('id', 'desc')->get();
        return response()->json($categorias, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //primero validamos antes de guardar
        $request->validate([
            "nombre" => "required|unique:categorias"
        ]);
        $categoria = new Categoria();
        $categoria->nombre = $request->nombre;
        $categoria->detalle = $request->detalle;
        //ahora guardamos
        $categoria->save();
        //retornamos
        return response()->json(["mensaje"=> "Categoria registrada"], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $categoria = Categoria::find($id); //busqueda por id
        //retornamos
        return response()->json($categoria, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //debemos de validar la categoria
        $request->validate([
            //validamos que nombre sea el unico verificando el campo nombre
            //excepto el registro actual o el mismo identificador (mismo nombre)
            //"required|unique:categorias,nombre,$id" sin espacios
            "nombre" => "required|unique:categorias,nombre,$id"
        ]);
        //necesitamos buscar la categoria
        $categoria = Categoria::find($id);
        //reemplazar la información
        $categoria->nombre = $request->nombre;
        $categoria->detalle = $request->detalle;
            //ahora guardamos
        $categoria->save();
        //finalmente la respuesta 
        return response()->json(["mensaje"=> "Categoria Actualizada"], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //primero buscamos 
        $categoria = Categoria::find($id);
        //ahora eliminamos
        $categoria->delete();
        //devolvemos una respuesta
        return response()->json(["mensaje"=> "Categoria Eliminada"], 200);
        
    }
}
