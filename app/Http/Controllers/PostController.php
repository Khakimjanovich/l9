<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return Post::paginate();
    }

    public function store(Request $request)
    {
        return Post::create($request->all());
    }

    public function show(Post $post)
    {

    }
}
