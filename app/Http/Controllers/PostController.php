<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function List(Request $request) {
        return Post::all();
    }

    public function ListOne(Request $request, $id_post) {
        return Post::findOrFail($id_post);
    }

    public function CreatePost(Request $request) {

        $nuevoPost = new Post();

        $nuevoPost -> text = $request ->post("text");
        $nuevoPost -> location = $request ->post("location");
        //$nuevoPost -> fk_id_user = $request ->post("fk_id_user");         como hago? con el token?
        //votos se deja vacio, porque apenas se crea el post no tiene votos de ningun tipo 
        //date tambien esta vacio porque se guarda el datetime (curdate), pero se puede con laravel?

        $nuevoPost -> save();
        return $nuevoPost;
    }

    public function Delete(Request $request, $id_post) {
        $post = Post::findOrFail($id_post);
        $post -> delete();
        return [ "response" => "Object with ID $id_post deleted"];
    }

/*
$table->id('id_post');
$table->unsignedBigInteger('fk_id_user');
$table->string('text')->nullable();
$table->string('location')->nullable();
$table->dateTime('date');
$table->integer("votes")->default(0);
*/

/* NO VA PORQUE UN POST NO SE DEBERIA PODER MODIFICAR
    public function Modificar(Request $request, $id_post){
        $animalito = Animal::findOrFail($id_post);
        $animalito -> nombre = $request ->post("nombre");
        $animalito -> cantidad_de_patas = $request ->post("patas");
        $animalito -> especie = $request ->post("especie");
        $animalito -> cola = $request ->post("cola");

        $animalito -> save();
        return $animalito;
    }

    public function Modificar(Request $request, $id){
        $animalito = Animal::findOrFail($id);
        $animalito -> nombre = $request ->post("nombre");
        $animalito -> cantidad_de_patas = $request ->post("patas");
        $animalito -> especie = $request ->post("especie");
        $animalito -> cola = $request ->post("cola");

        $animalito -> save();

        return $animalito;
    }
*/


}
