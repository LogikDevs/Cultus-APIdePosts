<?php

namespace App\Http\Controllers;

use App\Models\Characterizes;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CharacterizesController extends Controller
{
    public function ListPostLabels(Request $request, $id_post) {
        return Characterizes::where('fk_id_post', $id_post)->get();
    }

    public function ListLabelPosts(Request $request, $id_label) {
        return Characterizes::where('fk_id_label', $id_label)->get();
    }

    public function CreateCharacterizes(Request $request) {
        $validator = Validator::make($request->all(), [
            'fk_id_label'=>'required | exists:interest_label,id_label',
            'fk_id_post'=>'required | exists:post,id_post'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        return $this->SaveCharacterizes($request);
    }

    public function SaveCharacterizes(request $request) {
        $newCharacterizes = new Characterizes();
        $newCharacterizes -> fk_id_label = $request->input('fk_id_label');
        $newCharacterizes -> fk_id_post = $request->input('fk_id_post');
        $newCharacterizes -> save();
            
        return $newCharacterizes;
    }
}
