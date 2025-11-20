<?php
// Aqui se van a crear las funciones que necesita un sistema de autenticacion
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
   //para autenticar un usuario
   # necesitamos datos asi que inyectamos una dependencia "Request $request"
   //$request llega como parámetro trayendo el email y el password  
    function loginLaravel(Request $request) {
        // 1.-  Primero validamos (otra forma es usando el metodo validation q tamb. debemos importarlo)
        $request->validate([
            "email" => "required|email|string",
            "password" => "required|string"
        ]);
        // 2.- capturar las credenciales del $request que tiene el email y el password
        //la funcion request es diferente a $request 
        $credenciales = request(["email", "password"]);
        //autenticamos o verificamos y capturamos al usuario actual con el "attempt"
        if(!Auth::attempt($credenciales)){  //si no esta logueado entonces no podrá entrar
            return response()->json([
                "mensaje" => "No autorizado"
            ],401); //el 401 es el mensaje que indica que la solicitud no se pudo completar 
            //porque carece de credenciales de autenticación válidas para acceder al recurso
        }

        //ya esta logueado ahora capturamos al usuario el user es del modelo Users.php
        $usuario = $request->user();
        //una vez capturado al usuario debemos de capturar el $token
        $tokenResult = $usuario->createToken('Personal token');
        $token = $tokenResult->plainTextToken;

        return response()->json([
            'acces_token' => $token, //devolvemos el token
            'token_type' => 'Bearer', //devolvemos al portador del token
            //tambien se puede enviar al usuario
        ]);
   }

//    aqui va a llegar dato y lo capturamos en $reqest (inyección de dependencia)
   function registro(Request $request) {
        #para guardar un nuevo user en la bade de ratos
        // 1.- validar capturar datos del request las reglas se mandan como array
        $request->validate([
            "name" => "required",
            "email" => "required|unique:users|email",
            "password" => "required|string",
            "c_password" => "required|same:password" //repetir el mismo password
        ]);

        // 2.- guardar (creamos un objeto)
        //$usuario = new User($request->all()); // estos almacenan muchos en una sola linea

        $usuario = new User();
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->password = bcrypt($request->password);
        //para guardar una contraseña necesitomos cifrarla o encriptarla
        //con bycrypt o con la clase hash::make
        $usuario->save();

        //retornar el mesaje 
        return response()->json(["mensaje"=> "Usuario Registrado"]);
   }

    //obtener el perfil es obtener al usuario actual q esta navegando con Auth::user();
   //middlewer evitará que solo entre aqui el usuario logueado
   function perfil() {
        $user = Auth::user();
        return response()->json($user, 200);
    
   }

   function salir() {
        Auth::user()->tokens()->delete();
        return response()->json(["Tokens eliminados"]);
   }


}
