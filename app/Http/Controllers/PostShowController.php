<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostShowController extends Controller
{

    public function __invoke($id)
    {
        //
        return Post::find($id);
    }
}
