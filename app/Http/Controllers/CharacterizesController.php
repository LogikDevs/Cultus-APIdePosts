<?php

namespace App\Http\Controllers;

use App\Models\Characterizes;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CharacterizesController extends Controller
{

    public function ListAllCharacterizes(Request $request) {
        return Characterizes::all();
    }

    public function ListPostLabels(Request $request, $id_post) {
        return Characterizes::where('fk_id_post', $id_post)->get();
    }

    public function ListLabelPosts(Request $request, $id_label) {
        return Characterizes::where('fk_id_label', $id_label)->get();
    }

    public function CreateCharacterizes(Request $request) {
        $validation = $request->validate([
            'fk_id_label'=>'required | exists:interest_label,id_label',
            'fk_id_post'=>'required | exists:post,id_post'
        ]);

        $characterize = Characterizes::create($validation);
        return response()->json($characterize, 201);
    }

    public function DeletePost($id_post) {
        $characterizes = Characterizes::where('fk_id_post', $id_post)->get();
        $characterizes -> delete();
    }

/*
    public function Delete(Request $request, $id_characterizes) {
        $characterizes = Characterizes::findOrFail($id_characterizes);
        $characterizes -> delete();
    }
*/
}
