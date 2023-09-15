<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\VotesController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\CharacterizesController;
use App\Http\Controllers\MultimediaPostController;
use App\Http\Middleware\Autenticacion;
 

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('v1')->middleware(Autenticacion::class)->group(function(){
    Route::get('/posts', [PostController::class, 'ListAllPosts']);
    Route::get('/posts/list/{id_post}', [PostController::class, 'ListOnePost']);
    Route::get('/posts/user/{id_user}', [PostController::class, 'ListUserPosts']);
    Route::get('/posts/user', [PostController::class, 'ListOwnedPosts']);
    Route::get('/posts/followed', [PostController::class, 'ListFollowed']);
    Route::get('/posts/interested', [PostController::class, 'ListInterested']);
    Route::post('/posts/create', [PostController::class, 'CreatePost']);
    Route::post('/posts/delete/{id_post}', [PostController::class, 'Delete']);


    Route::get('/votes/listAll', [VotesController::class, 'ListAllVotes']);
    Route::get('/votes/listUser/{id_user}', [VotesController::class, 'ListOwnedVotes']);
    Route::get('/votes/listPost/{id_post}', [VotesController::class, 'ListPostVotes']);
    Route::post('/votes/create', [VotesController::class, 'ValidateVote']);
    Route::post('/votes/delete/{id_vote}', [VotesController::class, 'Delete']);

    Route::get('/comments', [CommentsController::class, 'ListAll']);
    Route::get('/comments/listUser', [CommentsController::class, 'ListOwnedComments']);
    Route::get('/comments/listPost/{id_post}', [CommentsController::class, 'ListPostComments']);
    Route::post('/comments/create', [CommentsController::class, 'CreateComment']);
    Route::post('/comments/delete/{id_comment}', [CommentsController::class, 'Delete']);

    Route::get('/characterizes/listPost/{id_post}', [CharacterizesController::class, 'ListPostLabels']);
    Route::get('/characterizes/listLabel/{id_label}', [CharacterizesController::class, 'ListLabelPosts']);
    Route::post('/characterizes/create', [CharacterizesController::class, 'CreateCharacterizes']);
    Route::post('/characterizes/delete/{id_characterizes}', [CharacterizesController::class, 'Delete']);

    Route::get('/multimedia/listAll', [MultimediaPostController::class, 'ListAll']);
    Route::get('/multimedia/listPost/{id_post}', [MultimediaPostController::class, 'ListMultimediaPost']);
    Route::post('/multimedia/create', [MultimediaPostController::class, 'ValidateMultimedia']);
    Route::post('/multimedia/delete/{id_post}', [MultimediaPostController::class, 'Delete']);
});