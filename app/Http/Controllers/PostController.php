<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class PostController extends Controller
{
    //
    public function show(Post $post)
    {
        $post->increment('views');
        if ($post->save()) {
            // zincrby 方法 对有序集合中指定成员的分数加上增量 increment
            Redis::zincrby('popular_posts', 1, $post->id);
        }
        return 'Show Post #' . $post->id;
    }

    //读取有序集合元素生成排行榜
    public function popular()
    {
        //获取浏览量最多的前十篇文章
        // zrevrange 方法 命令返回有序集中，指定区间内的成员。 其中成员的位置按分数值递减(从大到小)来排列。
        $postIds = Redis::zrevrange('popular_posts', 0, 9);
        if ($postIds) {
            $idsStr = implode(',', $postIds);
            //查询结果排序必须和传入时的 ID 排序一致
            $posts = Post::query()->whereIn('id', $postIds)
                ->select(['id', 'title', 'views'])
                ->orderByRaw('field(`id`, ' . $idsStr . ')')
                ->get();
        } else {
            $posts = null;
        }
        dd($posts->toArray());
    }
}
