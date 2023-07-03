<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MultimediaPostController extends Controller
{
    /*
    public function List(Request $request) {
        return MultimediaPost::all();
    }

    public function ListOne(Request $request, $id_post) {
        return MultimediaPost::findOrFail($id_post);
    }

    public function CreatePost(Request $request) {

        $multimediaPost = new MultimediaPost();

        $multimediaPost -> text = $request ->post("text");
        $multimediaPost -> location = $request ->post("location");
        //$nuevoPost -> fk_id_user = $request ->post("fk_id_user");         como hago? con el token?
        //votos se deja vacio, porque apenas se crea el post no tiene votos de ningun tipo 
        //date tambien esta vacio porque se guarda el datetime (curdate), pero se puede con laravel?

        $multimediaPost -> save();
        return $multimediaPost;
    }

    public function Delete(Request $request, $id_post) {
        $multimediaPostt = MultimediaPost::findOrFail($id_post);
        $multimediaPostt -> delete();
        return [ "response" => "Object with ID $id_post deleted"];
    }
    */
}
