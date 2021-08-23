<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Repos\PostRepo;
use Illuminate\Http\Request;

class PostController extends Controller
{

    protected PostRepo $postRepo;

    public function __construct(PostRepo $postRepo)
    {
        $this->postRepo = $postRepo;
    }

    //
    public function show($id)
    {
        $post = $this->postRepo->getById($id);
        $views = $this->postRepo->addViews($post);
        return 'Show Post #' . $post->id . ', Views:' . $views;
    }

    //读取有序集合元素生成排行榜
    public function popular()
    {
        $posts = $this->postRepo->trending(10);
        if ($posts) {
            dump($posts->toArray());
        }
    }
}
