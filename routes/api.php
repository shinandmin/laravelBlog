<?php
use App\Http\Controllers\AppController;
use App\Http\Controllers\PostController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


//Route::get('/ping', function() {
//    return 'pong';
//});
//Route::get('/ping', function() {
//    $ss = json_encode(['message' => 'pong']);
//    $dd = json_decode($ss);
//    echo $dd->message;
//});
Route::get('/posts/', [PostController::class, 'index']);
Route::get('/posts/{id}', [PostController::class, 'read']);
Route::post('/posts/', [PostController::class, 'create']);
Route::patch('/posts/{id}', [PostController::class, 'update']);
Route::delete('/posts/{id}', [PostController::class, 'delete']);


