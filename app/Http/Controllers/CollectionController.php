<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    //
    public function collection()
    {
        $posts = Post::all()->groupBy(function ($item) {
            return $item->created_at->format('Y-m-d');
        });

        return $posts->count();
    }

    public function paginate()
    {
        $query = Post::query();

        $sum = $query->sum('views');

        $post = $query->paginate(10);

        return $sum;
    }
}
