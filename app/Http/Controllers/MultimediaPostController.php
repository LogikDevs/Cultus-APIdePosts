<?php

namespace App\Http\Controllers;

use App\Models\MultimediaPost;
use App\Models\Post;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class MultimediaPostController extends Controller
{
    public function ListAll(Request $request) {
        return MultimediaPost::all();
    }
    
    public function ValidateMultimedia(Request $request) {
        $validator = Validator::make($request->all(), [
            'fk_id_post' => 'required | exists:post,id_post',
            'multimedia_file' => 'required | image | mimes:jpeg,png,mp4 | max:5120'
        ]);

        return $this->ValidateRequest($request, $validator);
    }

    public function ValidateRequest(Request $request, $validator) {
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        return $this->SaveMultimedia($request);
    }

    public function SaveMultimedia(Request $request) {
        $file = $request->file('multimedia_file');
        $fileData = $this->GetFileData($file);

        $fileName = Str::random(50) . "." . $fileData['file_extension'];
        $destinationPath = 'multimedia_post';
        $file->move($destinationPath, $fileName);

        return $this->CreateMultimedia($request, $fileName);
    }

    public function GetFileData($file) {
        $originalName = $file->getClientOriginalName();
        $fileExtension = $file->getClientOriginalExtension();
        $fileSize = $file->getSize();
        $mimeType = $file->getMimeType();

        return [
            'original_name' => $originalName,
            'file_extension' => $fileExtension,
            'file_size' => $fileSize,
            'mime_type' => $mimeType,
        ];
    }

    public function CreateMultimedia(Request $request, $fileName) {
        $imagen = new MultimediaPost();
        $imagen -> multimediaLink = $fileName;
        $imagen -> fk_id_post = $request->input('fk_id_post');
        return response ($this->TransactionSaveMultimedia($imagen), 201);
    }

    public function TransactionSaveMultimedia($imagen) {        
        try {
            DB::raw('LOCK TABLE multimedia_post WRITE');
            DB::beginTransaction();
            $imagen -> save();
            DB::commit();
            DB::raw('UNLOCK TABLES');
            return $imagen;
        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return $th->getMessage();
        }
        catch (\PDOException $th) {
            return response("Permission to DB denied",403);
        }
    }
}