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
    Route::get('/posts/event/{id_event}', [PostController::class, 'GetPostsFromEvent']);
    Route::get('/posts/list/{id_post}', [PostController::class, 'ListOnePost']);
    Route::get('/posts/user/{id_user}', [PostController::class, 'ListUserPosts']);
    Route::get('/posts/followed', [PostController::class, 'ListFollowed']);
    Route::get('/posts/interested', [PostController::class, 'ListInterested']);
    Route::post('/posts/create', [PostController::class, 'CreatePost']);
    Route::delete('/posts/delete/{id_post}', [PostController::class, 'Delete']);

    Route::get('/votes', [VotesController::class, 'ListAllVotes']);
    Route::post('/votes/create', [VotesController::class, 'ValidateRequest']);

    Route::get('/comments', [CommentsController::class, 'ListAll']);
    Route::post('/comments/create', [CommentsController::class, 'CreateComment']);
    Route::delete('/comments/delete/{id_comment}', [CommentsController::class, 'Delete']);

    Route::post('/characterizes/create', [CharacterizesController::class, 'CreateCharacterizes']);

    Route::get('/multimedia', [MultimediaPostController::class, 'ListAll']);
    Route::post('/multimedia/create', [MultimediaPostController::class, 'ValidateMultimedia']);
});