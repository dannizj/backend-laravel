<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Validator;
use OpenAI;

//use App\Helpers\JwtAuth;

class ArticleController extends Controller{

    public function save(Request $request){
        // Sacar los datos de usuario identificado
        $identity = $request->user;

        // Recoger los datos enviados en la peticion
        $title = $request->input("title", null);
        $content = $request->input("content", null);

        // Preparar el articulo a guardar
        $articleToSave = [
            "title" => $title,
            "content" => $content,
            "user_id" => $identity->sub
        ];

        // Validar los datos
        $validator = Validator::make($articleToSave, [
            "title" => "required",
            "content" => "required",
            "user_id" => "required",
            
        ]);

        
        // Verificar si esta correcta la validacion
        if($validator->fails()){
            $data = [
                "satatus" => "error",
                "message" => "Los datos no son validos",
                "error" => $validator->errors()
            ];

        }else{
            // Crear objeto articulo
            $article = new Article();

            // Asignar valores
            $article->title = $title;
            $article->content = $content;
            $article->user_id = $identity->sub;

            // Guardo
            $article->save();

            $data = [
                "satatus" => "success",
                "message" => "Articulo guardado correctamente",
                "article" => $article
            ];

        }
        
        

        // Devuelvo respuesta
        return response()->json($data);


    }

    public function articles(Request $request, $page = 1){

        $itemsPerPage = 5;

        $article = Article::orderBy("created_at", "desc")->paginate($itemsPerPage, ["*"], "page", $page);

        $data = [
          "status" => "success",
          "page" => $article->currentPage(),
          "pages" => $article->lastPage(),
          "itemsPerPage" => $itemsPerPage,
          "totalArticles" => $article->total(),
          "articles" => $article->items()
        ];

        return response()->json($data);

    }

    public function article(Request $request, $id){

        $article = Article::with("user")->find($id);

        if(empty($article)){
            $data = [
                "status" => "error",
                "message" => "No existe el articulo",
            ];
        }else{

            //$article->makeHidden('user');
            
            $data = [
                "status" => "success",
                "article" => $article
                ];
            
        }

       

        return response()->json($data);

    }

    public function articlesByUser(Request $request, $user_id){

        $articles = Article::where("user_id", $user_id)->orderBy("created_at", "desc")->get();

        if($articles->isEmpty()){
            $data = [
                "status" => "error",
                "message" => "No hay articulos para mostrar",
            ];
        }else{

            //$article->makeHidden('user');
            
            $data = [
                "status" => "success",
                "articles" => $articles
            ];            
        }       

        return response()->json($data);

    }

    public function search($searchString){

        $search = "%{$searchString}%";
        $articles = Article::where("title", "LIKE", $search)
                    ->orWhere("content", "LIKE", $search)
                    ->orderBy("created_at", "desc")
                    ->get();

        if($articles->isEmpty()){
            $data = [
                "status" => "error",
                "message" => "No hay articulos para mostrar",
            ];
        }else{

            $data = [
                "status" => "success",
                "articles" => $articles
            ];            
        }       

        return response()->json($data);

    }

    public function delete(Request $request, $id){

        $article = Article::find($id);

        if(!empty($article)){

            if($article->user_id !== $request->user->sub){
                $data = [
                    "status" => "error",
                    "message" => "No tienes el permiso para elimiarlo"
                ];
            }else{

                $data = [
                    "status" => "success",
                    "article" => $article
                ];
                
                $article->delete();
            }

        }else{

            $data = [
                "status" => "error",
                "message" => "articulo No encontrado"
            ];            
        }       

        return response()->json($data);

    }

    public function update(Request $request, $id){

        $identity = $request->user;
        $article = Article::where("id", $id)->first();

        $title = $request->input("title", $article->title);
        $content = $request->input("content", $article->content);

        $articleToUpdate = [
            "title" => $title,
            "content" => $content

        ];

        $validator = Validator::make($articleToUpdate, [
            "title" => "required",
            "content" => "required"
        ]);


        if($validator->fails()){
            
            $data = [
                "status" => "error",
                "message" => "Los datos no se han validado bien",
                "error" => $validator->errors()
            ];

        }else{

            if($article->user_id !== $request->user->sub){
                $data = [
                    "status" => "error",
                    "message" => "No tienes el permiso para elimiarlo"
                ];
            }else{
                
                $articleUpdate = $article->update($articleToUpdate);

                $data = [
                    "status" => "success",
                    "update" => $articleUpdate,
                    "article" => $articleToUpdate
                ];      

            }

                 
        }       

        return response()->json($data);

    }

    public function upload(Request $request, $id){

       $image = $request->file("file0");
       $article = Article::find($id);

       $validator = Validator::make($request->all(), [
            "file0" => "required|image|mimes:jpg,jpeg,gif,png"
       ]);


        if($image && empty($validator->fails())){
            
            $image_name = time() . $image->getClientOriginalName();

            \Storage::disk("posters")->put($image_name, \File::get($image));

            if($article && \Storage::disk("posters")->exists($article->image)){
                \Storage::disk("posters")->delete($article->image);
            }

            if($article){
                $article->image = $image_name;
                $article->save();

                $data = [
                    "status" => "success",
                    "message" => "Imagen subida correctamente",
                    "article" => $article
                ];
            }else{
                $data = [
                    "status" => "error",
                    "message" => "Usuraio no encontrado"
                ];
            }            

        }else{

            $data = [
                "status" => "error",
                "message" => $validator->errors()
            ];            
        }       

        return response()->json($data);

    }

    public function poster($file){
        $disk = \Storage::disk("posters");

        if($disk->exists($file)){
            $path = $disk->path($file);
            return response()->file($path);
        }else{
            return response()->json([
                "status" => "error",
                "message" => "Imagen no encontrada"
            ]);
        }
    }

    public function generate(Request $request, $theme){
        // Inicializar el cliente de openAI
        $client = OpenAI::client(env("OPENAI_API_KEY"));

        // Definir el prompt
        $promptSystem = "
            Eres un redacto especializado en {$theme}. 

            El Usuario te va a pedir que redactes un articulo (titulo y texto para el contenido).
            
            Solo puedes responder con un articulo adecuado al tema que te pida el usuario,
            cualquier otra conversacion o respusta esta prohibida.

            El formato de salida del articulo deber ser el siguiente (JSON):
                {
                    \"title\": \"AQUI EL TITULO\"
                    \"content\": \"AQUI EL CONTENIDO\"
                }
            
            El titulo deber tener maximo 120 caracteres.
            El contenido deber tener maximo 1000 caracteres.
        ";

        $promptUser = "Necesuto que redactes un articulo de {$theme}";

        try {
            // Llamar al api de openai
            $response = $client->chat()->create([
                "model" => "gpt-4o-mini",
                "messages" => [
                    ["role" => "system", "content" => $promptSystem],
                    ["role" => "user", "content" => $promptUser]
                ],
                "max_tokens" => 700,
                "response_format" => ["type" => "json_object"]
            ]);

            // Extraer el contenido del articulo
            $articleObject = json_decode($response->choices[0]->message->content, true);

            // Validar articulo
            $validator = Validator::make($articleObject, [
                "title" => "required|string|max:120",
                "content" => "required|string"
            ]);

            if($validator->fails()){
                $data = [
                    "status" => "error",
                    "message" => "Error al validar el articulo",
                    "error" => $validator->errors()
                ];
            }

            // ID Del usuario autenticado
            $identity = $request->user;
            $userId = $identity->sub;

            // Agregar el user_id al objeto del articulo
            $articleObject["user_id"] = $userId;

            // Crear y guardar el articulo
            $article = Article::create($articleObject);

            if(!$article){
                $data = [
                    "status" => "error",
                    "message" => "Error al guardar el articulo" 
                ];
            }else{
                // Devolder resultado
                $data = [
                    "status" => "success",
                    "article" => $articleObject
                ];

            }

            


        } catch (\Exception $e) {
            $data = [
                "status" => "error",
                "message" => "Error al generar el articulo",
                "error" => $e->getMessage()
            ];
        }

      
        return response()->json($data);

    }


}