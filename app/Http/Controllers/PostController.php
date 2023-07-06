<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;





class PostController extends Controller {
    
//estos funcan lol pero me faltan el id de user
    public function ListAllPosts(Request $request) {
        return Post::all();
    }

    public function ListOwnedPosts(Request $request, $id_user) {
        //me falta el id del usuario, no es el 
        $id_user = 2;
        return Post::where('fk_id_user', $id_user)->get();;
    }

    public function Delete(Request $request, $id_post) {
        //tengo que conseguir el id del post
        $post = Post::findOrFail($id_post);
        $post -> delete();
        return [ "response" => "Object with ID $id_post deleted"];
    }

/*
    public function ListFollowedPosts(Request $request) {
        proximamente: se listaran todos los posts pertenecientes a usuarios seguidos por el user loggueado
    }

    public function ListDiscover(Request $request, $id_post) {
        proximamente: se listaran todos los posts pertenecientes a etiquetas de interes marcadas por el user loggueado
    }
*/





public function obtenerIdUsuarioAutenticado() {
    /*
    if (Auth::check()) {
        $usuarioAutenticado = Auth::user();
        $userId = $usuarioAutenticado->id;
        return $usuarioAutenticado;
    }
    return null;
    */

    //$userId = Auth::user()->id;


    //$user = Auth::user();
    //$userId = $user->id;

    $id = Auth::id();
    return $id;
}






    public function PostCreate(Request $request){
        //$this->obtenerIdUsuarioAutenticado();
        $validation = self::CreatePostValidation($request);

        if ($validation->fails())
        return $validation->errors();

        return $this -> CreatePost($request);
    }


                public function CreatePostValidation(Request $request){
                    $validation = Validator::make($request->all(),[
                        'text' => 'nullable | alpha:ascii | max:255',
                        'location' => 'nullable | alpha:ascii',
                    ]);
                    return $validation;    
                }


                public function CreatePost(Request $request) {
                    function obtenerId(Request $request) {
            /*            $accessToken = $request->bearerToken();
                        if (!$accessToken) {
                            return response()->json(['message' => 'Access token no proporcionado'], 401);
                        }
                        return response()->json(['id' => $userId]);
            */

            /*
                        $accessToken = $request->bearerToken();
                        if (!$accessToken) {
                            return response()->json(['message' => 'Access token no proporcionado'], 401);
                        }
                        // Obtener el usuario autenticado y su ID
                        $user = Auth::user();
                        $userId = $user->id;

                        return response()->json(['id' => $userId]);
                    }
            */




            //$user = $request->user();
            //$userId = $user->id;
        }


                    //obtenerId($request);
                    $nuevoPost = new Post();
                    $nuevoPost -> text = $request ->post("text");
                    $nuevoPost -> location = $request ->post("location");
                    //$nuevoPost -> fk_id_user = $request ->post("id_user");
                    
                    $nuevoPost -> fk_id_user = 2;





        /*
                    if (auth()->check()) {
                        // Obtener el ID del usuario autenticado
                        $userId = auth()->user()->id;
                        // Resto de la lógica de la API de posts
                        // Ejemplo de respuesta con el ID del usuario
                        return response()->json(['user_id' => $userId]);
                    } else {
                        // Usuario no autenticado
                        return response()->json(['message' => 'Usuario no autenticado'], 401);
                    }
        */




            /*
                    $id = auth('api')->id();
                    //$id = Auth::id();
                    return $id;
            */



                    //DEVUELVE NULL         $userId = optional(Auth::user())->id;
                    
                    //$userId = Auth::user()->id;
                    //return $userId;
                    //ANDA PERO DEVUELVE NULL
                    //$nuevoPost -> fk_id_user = $userId;





                    /* ESTE SIIIIIIIII ANDA PERO DEVUELVE NULL
                                $id = Auth::id();
                                return $id;
                    */
            //$nuevoPost -> fk_id_user = $userId;







                    //$user = Auth::user();
                    //$userId = $user->id;\
                    /*
                                        $userId = Auth::user()->id;
                                        echo $userId;
                                        $nuevoPost -> fk_id_user = $userId;
                    */
                    //$nuevoPost -> fk_id_user = Auth::id();
                    //$nuevoPost -> fk_id_user = $request ->post("fk_id_user");         como hago? con el token?
                    //votos se deja vacio, porque apenas se crea el post no tiene votos de ningun tipo 
                    //date tambien esta vacio porque se guarda el datetime (curdate), pero se puede con laravel?
                    $now = date('d-m-y H:i');
                    $nuevoPost -> date = $now;

                    $nuevoPost -> save();
                    return $nuevoPost;
                }
    






/*
//NO SE VA PORQUE UN POST NO SE DEBERIA PODER MODIFICAR
    public function Edit(user $request, $id_post){
        $post = Post::findOrFail($id_post);
        $post -> text = $request ->post("text"); 
        $post -> location = $request ->post("location");
        
        $post -> save();  
        return $post;
    }
*/

}
