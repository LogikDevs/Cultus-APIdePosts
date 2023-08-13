<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\Post;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
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
        return $this->saveComment($request);
    }

    private function saveComment(Request $request) {
        $newComment = new Comments();
        $newComment -> fk_id_user = $request ->input("fk_id_user");
        $newComment -> fk_id_post = $request ->input("fk_id_post");
        $newComment -> text = $request ->input("text");
        $newComment -> save();

        $postId = $request->input('fk_id_post');
        $this->UpdateCommentCount($postId);
        return $this->GetUser($request);
        return $newComment;
    }

    public function Delete($id_comment) {
        $comment = Comments::findOrFail($id_comment);
        $postId = $comment->fk_id_post;
        $comment -> delete();
        
        $this->UpdateCommentCount($postId);
    }

    private function UpdateCommentCount($postId) {
        $totalComments = Comments::where('fk_id_post', $postId)->count();
        $post = Post::find($postId);
        $post->comments = $totalComments;
        $post->save();
    }

    private function GetUser(Request $request) {
        $user = User::find($request ->input("fk_id_user"));
        return $user->only(['name', 'surname']);
    }
}
