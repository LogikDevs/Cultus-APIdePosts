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

    public function ListOwnedComments(Request $request, $id_user) {
        return Comments::where('fk_id_user', $id_user)->get();
    }

    public function ListPostComments(Request $request, $id_post) {
        return Comments::where('fk_id_post', $id_post)->get();
    }

    public function CreateComment(Request $request){
        $validation = [
            'fk_id_user' => 'required | exists:users,id',
            'fk_id_post' => 'required | exists:post,id_post',
            'text' => 'required | max:255',
        ];
    
        $request->validate($validation);
        //$this->saveComment($request);
        //$this->UpdateCommentCount($request);


        $newComment = $this->saveComment($request);
        $this->UpdateCommentCount($newComment->fk_id_post);
    }

            private function saveComment(Request $request) {
                $newComment = new Comments();
                $newComment -> fk_id_user = $request ->input("fk_id_user");
                $newComment -> fk_id_post = $request ->input("fk_id_post");
                $newComment -> text = $request ->input("text");
                $newComment -> save();

                return $newComment;
            }






 
    public function Delete(Request $request, $id_comment) {
        $comment = Comments::findOrFail($id_comment);
    $postId = $comment->fk_id_post;
        $comment -> delete();
    $this->UpdateCommentCount($postId);

        //$this->UpdateCommentCount($request);
        //$a = Comments::where('fk_id_post', $request->post("fk_id_post"))->get()->count();
        //return $a;
    }


    //private function UpdateCommentCount(Request $request) {
    /*
        $totalComments = Comments::where('fk_id_post', $request->input("fk_id_post"))->get()->count();
        $post = Post::find($request->input("fk_id_post"));
        $post -> comments = $totalComments;
        $post -> save();
    */

    private function UpdateCommentCount($postId) {
        $totalComments = Comments::where('fk_id_post', $postId)->count();
        $post = Post::find($postId);
        $post->comments = $totalComments;
        $post->save();

        return "estamos en la funcion";
        //return $totalComments;
    }
}
