<?php

namespace App\Http\Controllers;

use App\Events\PostViewed;
use App\Jobs\PostViewsIncrement;
use App\Models\Post;
use App\Repos\PostRepo;
use Illuminate\Contracts\Redis\LimiterTimeoutException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class PostController extends Controller
{

    protected PostRepo $postRepo;

    public function __construct(PostRepo $postRepo)
    {
        $this->postRepo = $postRepo;
    }

    //
//    public function show($id)
//    {
////        $post = $this->postRepo->getById($id);
////        $views = $this->postRepo->addViews($post);
////        return 'Show Post #' . $post->id . ', Views:' . $views;
//        $post = $this->postRepo->getById($id);
//        // 分发队列任务
//        $this->dispatch(new PostViewsIncrement($post));
//        return "Show Post #{$post->id}, Views: {$post->views}";
//    }

    // 基于 漏斗算法 实现的 Redis 限流器使用 Redis 返回 ConcurrencyLimiter 限流器对应的构建器实例
//    public function show($id)
//    {
//        //  定义一个限定并发访问频率的限流器，最多支持 100 个并发请求
//        try {
//            return Redis::funnel("posts.${id}.show.concurrency")
//                ->limit(100)
//                ->then(function () use ($id) {
//                    // 正常访问
//                    $post = $this->postRepo->getById($id);
//                    event(new PostViewed($post));
//                    return "Show Post #{$post->id}, Views: {$post->views}";
//                }, function () {
//                    // 触发并发访问上限
//                    abort(429, 'Too Many Requests');
//                });
//        } catch (LimiterTimeoutException $e) {
//            return $e->getMessage();
//        }
//    }

    // 限定单位时间访问上限 通过 Redis::throttle 方法返回 DurationLimiter 限流器对应的构建器实例
    public function show($id)
    {
        try {
            return Redis::throttle("posts.${id}.show.concurrency")
                ->allow(100)->every(10)
                ->then(function () use ($id) {
                    // 正常访问
                    $post = $this->postRepo->getById($id);
                    event(new PostViewed($post));
                    return "Show Post #{$post->id}, Views: {$post->views}";
                }, function () {
                    // 触发并发访问上限
                    abort(429, 'Too Many Requests');
                });
        } catch (LimiterTimeoutException $e) {
            return $e->getMessage();
        }
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
