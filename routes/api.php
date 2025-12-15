<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// servicios para la autenticación WEB-SERVICE

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// aqui vamos a agrupar nuestras rutas
//primero importar AuthController linea 3
//bersionamiento de APIS
Route::group(['prefix' => 'v1/auth'], function(){
    //loginLaravel es una función q deberia estar creado dentro de AuthController
    //y la ruta se llama login, pero noten que ya hay un prefijo
    //la ruta seria: /api/v1/auth/login
    Route::post('login', [AuthController::class, 'loginLaravel']);
    Route::post('registro', [AuthController::class, 'registro']);
    //logout y perfil tienen algo especial ya q para aceder a estos debo estar logueado

    //middleware solo verifica si estas autenticado o No. para proihibir o permitir
    //auth:sanctum verifica si el token es el corecto pero aun no generamos ningún token
    //Si no estamos logueados no podremos ingresar a las rutas de abajo
    Route::group(["middleware" => "auth:sanctum"], function(){
        // /api/v1/auth/perfil   get ya que quiero obtener el perfil
        //obtener el perfil es obtener al usuario actual q esta navegando
        Route::get("perfil", [AuthController::class, "perfil"]);
        //para cerrar sesion tienes que estar logueado
        Route::post("logout", [AuthController::class, "salir"]);

    });

});

//CRUD API formamos las rutas o puntos de acceso ENDPOINTS
Route::apiResource("categoria", CategoriaController::class);
Route::apiResource("producto", ProductoController::class);
Route::apiResource("cliente", ClienteController::class);
Route::apiResource("pedido", PedidoController::class);


Route::get("/no-autorizado", function(){
    return ["mensaje" => "No tienes permiso para acceder a esta pagina"];
})->name("login");