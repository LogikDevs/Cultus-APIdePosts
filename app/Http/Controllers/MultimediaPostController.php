<?php

namespace App\Http\Controllers;

use App\Models\MultimediaPost;
use App\Models\Post;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class MultimediaPostController extends Controller
{
    public function ListAll(Request $request) {
        return MultimediaPost::all();
    }

    public function ListMultimediaPost ($id_post) {
        return MultimediaPost::where('fk_id_post', $id_post)->get();
    }

    public function ValidateMultimedia(Request $request) {
        $validator = Validator::make($request->all(), [
            'fk_id_post' => 'required | exists:post,id_post',
            'multimedia_file' => 'required | image | mimes:jpeg,png,mp4 | max:5120'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        return $this->SaveMultimedia($request);
    }

    public function SaveMultimedia (Request $request) {
        $mediaPost = new MultimediaPost();
        if ($request->hasFile('multimedia_file')){
            $image = $request->file('multimedia_file');
            $imageExtension = $image->getClientOriginalExtension();
            $path = $image->store('/public/multimedia_post');
            $mediaPost -> multimediaLink = basename($path);
        }
        $mediaPost -> fk_id_post = $request->input('fk_id_post');
        $mediaPost -> save();

        return response()->json($mediaPost, 201);
    }
}