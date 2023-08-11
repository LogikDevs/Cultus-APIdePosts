<?php

namespace App\Http\Controllers;

use App\Models\MultimediaPost;
use App\Models\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MultimediaPostController extends Controller
{

    public function ListAll(Request $request) {
        return MultimediaPost::all();
    }

    public function ListMultimediaPost ($id_post) {
        return MultimediaPost::where('fk_id_post', $id_post)->get();
    }

    public function SaveMultimedia (Request $request) {
    /*
    FUNCIONA TAMBIEN
        $fk_id_post = $request->input('fk_id_post');
        $multimedia_file = $request->file('multimedia_file');
        $path = $multimedia_file->store('uploads', 'public');
       
        $multimedia = new MultimediaPost([
            'fk_id_post' => $fk_id_post,
            'multimediaLink' => $path
        ]);
        $multimedia->save();
    */

        $this->validate($request, [
            'fk_id_post' => 'required | exists:post,id_post',
            'multimedia_file' => 'required | file | mimes:jpeg,png,mp4 | max:2048', // Asumiendo que solo permitimos imágenes y videos con un tamaño máximo de 2MB
        ]);

        $mediaPost = new MultimediaPost();
        $mediaPost->fk_id_post = $request->input('fk_id_post');

        // Subir el archivo multimedia al servidor y obtener su ruta en el sistema de archivos
        $file = $request->file('multimedia_file');
        $path = $file->store('public/multimedia_post');

        // Almacenar la ruta en la base de datos
        $mediaPost->multimediaLink = $path;
        $mediaPost->save();

        return response()->json($mediaPost, 201);
    }

    public function Delete($id_post) {
        /*
            $mediaPost = MultimediaPost::findOrFail($id);
    
            // Eliminar el archivo multimedia del servidor
            if (file_exists(public_path($mediaPost->multimediaLink))) {
                unlink(public_path($mediaPost->multimediaLink));
            }
    
            $mediaPost->delete();
    
            return response()->json(null, 204);
    */

    /*
        $post = MultimediaPost::where('fk_id_post', $id_post)->get();
        // Eliminar el archivo multimedia del servidor
        if (file_exists(public_path($post->multimediaLink))) {
            unlink(public_path($post->multimediaLink));
        }

        $post->delete();

        return response()->json(null, 204);
    }
    */
    }
}