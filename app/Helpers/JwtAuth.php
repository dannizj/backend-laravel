<?php
namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use Illuminate\Support\Facades\DB;
use App\Models\User;

class JwtAuth{
    // Clave secreta
    private $key;

    // Inicializar clave secreta en un constructor
    public function __construct(){
        $this->key = config('services.jwt.secret');
    }

    // Generar token JWT para el usuario autenticado
     public function auth($user, $getuser = null){
        $signup = false;

        if(is_object($user)){
            $signup = true;
        }

        if($signup){
            //Datos del payload
            $payload = [
                "sub" => $user->id,
                "email" => $user->email,
                "name" => $user->name,
                "surname" => $user->surname,
                "nick" => $user->nick,
                "bio" => $user->bio,
                "avatar" => $user->avatar,
                "created_at" => $user->created_at,
                "iat" => time(),
                "exp" => time() + (30 * 24 * 60 * 60)

            ];

            //Codificar el payload
            $jwt = JWT::encode($payload, $this->key, "HS256");
            
            if(is_null($getuser)){
                $data = $jwt;
            }else{
                //Decodificar el token para sacar datos del usuario (getuser)
                $user_decoded = JWT::decode($jwt, new Key($this->key, "HS256"));

                //Devolver respuesta
                $data = $user_decoded;

            }
            
        }else{
            $data = [
                "status" => "error",
                "message" => "Login fallido"
            ];
        }

        return $data;
    }
    
    // Verificar la validez de un token
    

    
}