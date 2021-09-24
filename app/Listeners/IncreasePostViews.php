<?php

namespace App\Listeners;

use App\Events\PostViewed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Redis;

class IncreasePostViews implements ShouldQueue
{
    use InteractsWithQueue;

    public string $queue = 'events';

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle(PostViewed $event)
    {
//        //
//        if ($event->post->increment('views')) {
//            Redis::zincrby('popular_posts', 1, $event->post->id);
//        }
        // 通过限流器限制队列任务处理频率
//        Redis::funnel("post.views.increment")
//            ->limit(60)
//            ->then(function () use ($event) {
//                //
//                if ($event->post->increment('views')) {
//                    Redis::zincrby('popular_posts', 1, $event->post->id);
//                }
//            }, function () {
//                $this->release(60);
//            });
        // 通过时间窗口限定处理频率 每分钟最多执行 60 次
        Redis::throttle("posts.views.increment")
            ->allow(60)->every(60)
            ->then(function () use ($event) {
                if($event->post->increment('view')){
                    Redis::zincrby('popular_posts', 1, $event->post->id);
                }
            }, function () {
                $this->release(60);
            });
    }
}
