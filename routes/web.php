<?php

use App\Http\Controllers\PostController;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function (Request $request) {

    if ($request->has('error')) {
        throw new Exception('whoops');
    }

    if ($request->has('str')) {

        return [
            Str::of('Hello world')->upper()->append(' opthers')->toString(),
            str('hello world')->upper()->append(' and everyone else')->toString(),
        ];
    }

    if ($request->has('blade')) {
        return Blade::render('{{$greeting}} @if(!request()->name) world @else <h1>{{request()->name}}</h1> @endif', ['greeting' => $request->get('greeting')]);
    }

    return view('welcome');
})->name('welcome');

Route::get('endpoint', function () {
    return to_route('welcome');
});

Route::controller(PostController::class)->prefix('posts')->group(function () {
    Route::get('/', 'index');
    Route::get('/{post}', 'show');
    Route::post('/', 'store');
});


//laravel 8 way
Route::get('users/{user}/posts/{post:id}', function (User $user, Post $post) {
    return response()->json($post);
});

//laravel 9 way
Route::get('users-l9/{user}/posts/{post}', function (User $user, Post $post) {
    return response()->json($post);
})->scopeBindings();
