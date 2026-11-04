<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Helpers\JwtAuth;

class UserController extends Controller
{
    public function register(Request $request){
        //Recoger los datos del usuario
        $name = $request->input("name", null);
        $surname = $request->input("surname", null);
        $email = $request->input("email", null);
        $nick = $request->input("nick", null);
        $password = $request->input("password", null);

        //Crear array con los datos
        $user = [
            "name" => $name,
            "surname" => $surname,
            "email" => $email,
            "nick" => $nick
        ];

        //Eliminar espacios en blanco
        $user_trim = array_map("trim", $user);
        $user_trim["password"] = $password;

        //Validar los datos
        $validator = Validator::make($user_trim, [
            "name" => "required|alpha",
            "surname" => "required|alpha",
            "email" => "required|email|unique:users",
            "nick" => "required|unique:users",
            "password" => "required"
        ]);

        //Crear respuesta en caso de errores
        $res = [
            "status" => "error",
            "message" => "Los datos no se han validado correctamente",
            "error" => $validator->errors()
        ];

        //Vericar si la validacion falla
        if($validator->fails()){
            return response()->json($res);
        }else{

            //Si no falla...

            //Cifrar el password
            $pwd = password_hash($user_trim["password"], PASSWORD_BCRYPT, ["cost" => 5]);

            //Crear un objeto User
            $user = new User();
            $user->name = $user_trim["name"];
            $user->surname = $user_trim["surname"];
            $user->email = $user_trim["email"];
            $user->nick = $user_trim["nick"];
            $user->password = $pwd;

            //Guardar el usuario en la bd
            $user->save();

            //Devolver Respuesta
            return response()->json([
                "status" => "success",
                "message" => "Usuario registrado correctamente",
                "user" => $user
            ]);
        }

       
    }

    public function login(Request $request){

    // Crear una instancia de JWT
    $jwtAuth = new JwtAuth();

    // Recoger los datos del usuario
    $email = $request->input("email", null);
    $password = $request->input("password", null);
    $getUser =$request->input("getUser", null);

    // Esos datos los meto en un array
    $user = [
        "email" => $email,
        "password" => $password
    ];

    // Validar los datos
    $validator = Validator::make($user, [
        "email" => "required|email",
        "password" => "required"
    ]);

    // Comprobar validacion
    if($validator->fails()){
        // di no es valida dar una respuesta
        
        return response()->json([
                "status" => "error",
                "message" => "Los datos no se han validado correctamente",
                "error" => $validator->errors()
            ]);

    }else{
        // Buscar al usuario en la bd para ver si existe
        $user = User::where([
            "email" => $email
        ])->first();

        // Verificar si las contraseñas es correcta
        $pwdVerify = password_verify($password, $user->password);

        // Comprobar si se ha pasado la verificacion
        if($pwdVerify){
            

            // Devolver respuesta positiva
            if(is_null($getUser)){
                return $jwtAuth->auth($user);
            }else{
                return $jwtAuth->auth($user, true);
            }

            

        }else{
            // Devolver respuesta negativa
            return response()->json([
                    "status" => "error",
                    "message" => "Login Incorrecto"
                ]);

            };
        }


        
    
    }

    
}
