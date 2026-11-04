<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\JwtAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Obtener token del encabezado
        $token = $request->header("Authorization");

        // Instanciar helper
        $jwtAuth = new JwtAuth();

        // Verificar el token
        if(!is_null($token)){
            $checkToken = $jwtAuth->checkToken($token);

        }else{
            return response()->json([
                "status" => "error",
                "message" => "Falta el token en autenticacion"
            ]);
        }

        // Vincular objeto a la request
        if($checkToken){
            $request->user = $jwtAuth->checkToken($token, true);

            return $next($request);

        }else{
            return response()->json([
                "status" => "error",
                "message" => "Login incorrecto. Token no valido"
            ]);
        }

        
    }
}
