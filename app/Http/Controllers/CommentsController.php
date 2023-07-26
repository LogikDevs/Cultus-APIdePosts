<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function ListComments(Request $request) {
        return Comments::all();
    }

    public function ListOwnedComments(Request $request) {
        $id_user = $request ->post("userId");
        return Comments::where('fk_id_user', $id_user)->get();
    }

    public function ListPostComments(Request $request) {
        $id_post = $request ->post("postId");
        $post = Post::findOrFail($id_post);
        $comments = $post->comments;
        return response()->json([
            'comments' => $comments
        ]);
    }

    public function CreateComment(Request $request) {
        $newComment = new Comments();
        $newComment -> fk_id_user = $request ->post("userId");
        $newComment -> fk_id_user = $request ->post("postId");
        $newComment -> text = $request ->post("text");

        $newComment -> save();
        return $newComment;
    }

    public function Delete($id_comment) {
        $comment = Comments::findOrFail($id_comment);
        $comment -> delete();
        return [ "response" => "Object with ID $id_comment deleted"];
    }



}
