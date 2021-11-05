<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostCollection;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //
    public function postsList()
    {
//        $posts = Post::all();
        $posts = PostCollection::collection(Post::query()->paginate(10));
        return $posts;
    }

    public function noContent()
    {
        return response()->noContent();
    }
}
