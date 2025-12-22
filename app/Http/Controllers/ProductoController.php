<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     * agregamos el (Request $request) para capturar nuevas variables -> &q=mesa
     * Y asi podamos hacer búsquedas por diferentes variables -> &q=mesa
     */
    public function index(Request $request)
    {
        //retornamos la lista de producto
        //no pregunamos cuantos productos vamos a recibir para paginar o no

        //la ruta podrá ser: localhost:8000/api/producto?page=1&q=mesa
        if($request->rows){
            // capturamos del fron el numero de filas que quiere el usuario que se muestren
            $filas = $request->rows;
        }else{
            $filas = 5;
        }
        if($request->q){
            // filtramos el tipo de orden de acuerdo al valor enviado por el front
            $productos = Producto::orwhere('nombre', 'like', '%'.$request->q.'%')
                                ->orwhere('precio', 'like', '%'.$request->q.'%')
                                ->paginate($filas);
        }else{
            // abajo le decimos q devuelva $filas datos de la pagina 1
        $productos = Producto::with('categoria')->paginate($filas);
        }
        //retorna la información
        return response()->json($productos, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //1ro validar, subir imagen, guardar informacion, respuesta
        $request->validate([
            "nombre" => "required|string|max:100|min:2",
            //precio y stock vienen por defecto con valor de 0, imagen y descripción no es obligatorio
            "categoria_id" => "required"
        ]);
        //subir imagen 1ro preguntampos si hay imagen y luego recien lo subimos
        //haremos una subida local. es decir en la carpeta public. ya q son pocas imagenes
        $imagen = "";
        if($file = $request->file("imagen")){
            //formamos la direccion de subida de la imagen concatenando para evitar duplicidad
            $direccion_imagen = time()."-".$file->getClientOriginalName();
            //lo subimos directamente a la carpeta public
            $file->move("imagenes/", $direccion_imagen);
            $imagen = "imagenes/".$direccion_imagen;//actualizamos la variable
        }
        //guardar datos
        $producto = new Producto();
        $producto->nombre = $request->nombre;
        $producto->precio = $request->precio;
        $producto->stock = $request->stock;
        $producto->categoria_id = $request->categoria_id;
        $producto->descripcion = $request->descripcion;
        $producto->imagen = $imagen;
        $producto->save();      

        // respuesta
        return response()->json(["mensaje" => "Producto registrado", 201]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //Buscar el producto por id y si no existe error 404
        //Producto con categoría with"categoria"
        // $producto = Producto::findOrFail($id);
        // $producto->categoria;
        $producto = Producto::with("categoria")->findOrFail($id);
        return response()->json($producto, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // validar
        $request->validate([
            "nombre" => "required|string|max:100|min:2",
            //precio y stock vienen por defecto con valor de 0, imagen y descripción no es obligatorio
            "categoria_id" => "required"
        ]);
        
        //guardar datos 1ro buscamos al producto por su id
        $producto = Producto::find($id);
        //una vez encontrado reemplazamos todos los campos
        $producto->nombre = $request->nombre;
        $producto->precio = $request->precio;
        $producto->stock = $request->stock;
        $producto->categoria_id = $request->categoria_id;
        $producto->descripcion = $request->descripcion;
           
        //subida de imagnes
        //1ro preguntampos si hay cambio de imagen y luego recien lo subimos
        //haremos una subida local. es decir en la carpeta public. ya q son pocas imagenes
        $imagen = $producto->imagen;
        if($file = $request->file("imagen")){
            //formamos la direccion de subida de la imagen concatenando para evitar duplicidad
            $direccion_imagen = time()."-".$file->getClientOriginalName();
            //lo subimos directamente a la carpeta public
            $file->move("imagenes/", $direccion_imagen);
            $imagen = "imagenes/".$direccion_imagen;//actualizamos la variable
            $producto->imagen = $imagen;
        }

        $producto->save();

        // respuesta
        return response()->json(["mensaje" => "Producto Actualizado", 200]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //1ro buscamos al producto por su id
        $producto = Producto::findOrFail($id);
        $producto->delete();
        // respuesta
        return response()->json(["mensaje" => "Producto Eliminado", 200]);
    }

    //llega la imagen en Request $request $id llega como parametro desde api.php
    public function actualizarImagen (Request $request, $id)
    {
        $imagen = "";
        //preguntamos si realmente llega la imagen para cargarlo al file
        if($file = $request->file("imagen")){
            //formamos la direccion de subida de la imagen concatenando para evitar duplicidad
            $direccion_imagen = time()."-".$file->getClientOriginalName();
            //lo subimos directamente a la carpeta public
            $file->move("imagenes/", $direccion_imagen);
            $imagen = "imagenes/".$direccion_imagen;//actualizamos la variable
            //para actualizar 1ro buscamos el producto por su $id identificador enviado por URL
            $producto = Producto::find($id);
            //una vez encontrado actualizamos solo su imagen
            $producto->imagen = $imagen; 
            //ahora guardamos 
            $producto->update();
        }
        return response()->json(["mensaje" => "Imagen Actualizada"], 200);
    }
}
