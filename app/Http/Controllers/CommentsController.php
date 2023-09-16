<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\Post;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function GetUserId(Request $request) {
        $tokenHeader = [ "Authorization" => $request -> header("Authorization")];
        $response = Http::withHeaders($tokenHeader)->get(getenv("API_AUTH_URL") . "/api/v1/validate");
        return $response['id'];
    }

    public function ListAll(Request $request) {
        return Comments::all();
    }

    public function ListOwnedComments(Request $request) {
        $id_user = $this->GetUserId($request);
        return Comments::where('fk_id_user', $id_user)->get();
    }

    public function ListPostComments(Request $request, $id_post) {
        return Comments::where('fk_id_post', $id_post)->get();
    }

    public function CreateComment(Request $request){
        $validator = Validator::make($request->all(), [
            'fk_id_post' => 'required | exists:post,id_post',
            'text' => 'required | max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        return $this->saveComment($request);
    }

    private function saveComment(Request $request) {
        $id_user = $this->GetUserId($request);
        $newComment = new Comments();
        $newComment->fk_id_user = $id_user;
        $newComment->fk_id_post = $request->input("fk_id_post");
        $newComment->text = $request->input("text");
        $newComment->save();

        $postId = $request->input('fk_id_post');
        $this->UpdateCommentCount($postId);
        $user = $this->GetUser($id_user);

        $response = [
            'comment' => $newComment,
            'user' => $user,
        ];

        return $response;
    }

    public function Delete(Request $request, $id_comment) {
        $id_user = $this->GetUserId($request);
        $comment = Comments::findOrFail($id_comment);
        
        if ($comment['fk_id_user'] == $id_user) {
            $comment -> delete();
            return response()->json(['mensaje' => 'Eliminado con exito.']);
        }

        return response()->json(['Error' => 'No puedes eliminar este comentario ya que no eres el creador.']);




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

    private function GetUser($id_user) {
        $user = User::find($id_user);
        return $user->only(['name', 'surname', 'profile_pic']);
    }
}
