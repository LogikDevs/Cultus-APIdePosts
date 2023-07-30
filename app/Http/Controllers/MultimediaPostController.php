<?php

namespace App\Http\Controllers;

use App\Models\MultimediaPost;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MultimediaPostController extends Controller
{

    /*
//File Upload Function
public function SaveImage(Request $request) {
      //check file
      if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $picture = date('His').'-'.$filename;
            //move image to public/img folder
            $file->move(public_path('img'), $picture);
            return response()->json(["message" => "Image Uploaded Succesfully"]);
      } 
      else
      {
            return response()->json(["message" => "Select image first."]);
      }
    }
    */

    public function store(Request $request) {
        $this->validate($request, [
            'fk_id_post' => 'required|exists:posts,id',
            'multimedia_file' => 'required|file|mimes:jpeg,png,mp4|max:2048', // Asumiendo que solo permitimos imágenes y videos con un tamaño máximo de 2MB
        ]);

        $mediaPost = new MultimediaPost();
        $mediaPost->fk_id_post = $request->input('fk_id_post');

        // Subir el archivo multimedia al servidor y obtener su ruta en el sistema de archivos
        $file = $request->file('multimedia_file');
        $path = $file->store('public/multimedia');

        // Almacenar la ruta en la base de datos
        $mediaPost->multimedia_link = $path;
        $mediaPost->save();

        return response()->json($mediaPost, 201);
    }

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
            $mediaPost->multimedia_link = $path;
        }

        $mediaPost->save();

        return response()->json($mediaPost);
    }

    public function destroy($id) {
        $mediaPost = MultimediaPost::findOrFail($id);

        // Eliminar el archivo multimedia del servidor
        if (file_exists(public_path($mediaPost->multimedia_link))) {
            unlink(public_path($mediaPost->multimedia_link));
        }

        $mediaPost->delete();

        return response()->json(null, 204);
    }

}