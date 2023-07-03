<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MultimediaPostController extends Controller
{
    
    public function List(Request $request) {
        return MultimediaPost::all();
    }

    public function ListOne(Request $request, $id_multimediaPost) {
        return MultimediaPost::findOrFail($id_multimediaPost);
    }

























    public function CreateMultimediaPost(Request $request) {

        $multimediaPost = new MultimediaPost();
        $multimediaPost -> multimediaLink = $request ->post("multimediaLink");

        $multimediaPost -> save();
        return $multimediaPost;
    }

    public function Delete(Request $request, $id_post) {
        $multimediaPostt = MultimediaPost::findOrFail($id_post);
        $multimediaPostt -> delete();
        return [ "response" => "Object with ID $id_post deleted"];
    }
    
}