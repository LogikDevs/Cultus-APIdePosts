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
        return "holiwi";
/*      
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
*/
/*
          // Validation
        $request->validate([
            'multimedia_file' => 'required | mimes:png,jpg,jpeg,csv,txt,pdf | max:2048'
        ]);
    */
        return $request->file('multimedia_file');
        if($request->file('multimedia_file')) {
                $file = $request->file('multimedia_file');
                $filename = time().'_'.$file->getClientOriginalName();
                return $filename;

                // File upload location
                $location = '/public/multimedia_post';

                // Upload file
                $file->move($location,$filename);

                return "god";
        }else{
                return "no muy god";
        }
        //return redirect('/');
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
    
            $post = MultimediaPost::where('fk_id_post', $id_post)->get();
            // Eliminar el archivo multimedia del servidor
            if (file_exists(public_path($post->multimediaLink))) {
                unlink(public_path($post->multimediaLink));
            }
    
            $post->delete();
    
            return response()->json(null, 204);
        

/*
    public function update(Request $request, $id) {
        $this->validate($request, [
            'fk_id_post' => 'exists:posts,id_post',
            'multimedia_file' => 'file | mimes:jpeg,png,mp4 | max:2048', // Asumiendo que solo permitimos imágenes y videos con un tamaño máximo de 2MB
        ]);

        $mediaPost = MultimediaPost::findOrFail($id);

        if ($request->has('fk_id_post')) {
            $mediaPost->fk_id_post = $request->input('fk_id_post');
        }

        // Si se proporciona un nuevo archivo multimedia, actualizarlo
        if ($request->hasFile('multimedia_file')) {
            $file = $request->file('multimedia_file');
            $path = $file->store('public/multimedia');
            $mediaPost->multimediaLink = $path;
        }

        $mediaPost->save();

        return response()->json($mediaPost);
    }
*/
    /*
        $mediaPost = MultimediaPost::findOrFail($id);

        // Eliminar el archivo multimedia del servidor
        if (file_exists(public_path($mediaPost->multimediaLink))) {
            unlink(public_path($mediaPost->multimediaLink));
        }

        $mediaPost->delete();

        return response()->json(null, 204);
*/





        $post = MultimediaPost::where('fk_id_post', $id_post)->get();
        // Eliminar el archivo multimedia del servidor
        if (file_exists(public_path($post->multimediaLink))) {
            unlink(public_path($post->multimediaLink));
        }

        $post->delete();

        return response()->json(null, 204);
    }

}