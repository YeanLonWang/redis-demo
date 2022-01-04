<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class PostViewsIncrement implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Post $post;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Post $post)
    {
        //
        $this->post = $post;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
//    public function handle()
//    {
//        //
//        if ($this->post->newQuery()->increment('views')) {
//            Redis::zincrby('popular_posts', 1, $this->post->id);
//        }
//    }
    public function handle()
    {
        // 运行任务前检查熔断器是否开启
        if ($lastFailureTimestamp = Cache::get('circuit:open')) {
            // 默认情况下，熔断器会开启 10 分钟，当它关闭后，我们不得不在触发熔断器阈值前再次发送 10 次请求，如果服务仍然不可用，则再次开启熔断器。具体实现思路是在熔断器关闭前发送一次请求，测试服务接口是否可用，进而决定是否真的关闭熔断器，如果请求成功，则关闭熔断器，否则继续开启 10 分钟。
            if (time() - $lastFailureTimestamp < 8 * 60) {
                //将任务推送回队列的延迟时间加上了一个随机值，变成 10-12 分钟，从而避免在熔断器关闭之后大量重试任务集中执行，对外部服务接口同时发起过多请求，进而触发频率限制机制或者让服务再度挂掉
                return $this->release(
                    $lastFailureTimestamp + 600 + rand(1, 120)
                );
            } else {
                // 半开启熔断器
                // 8 分钟作为一个分水岭，小于 8 分钟的话，熔断器处于打开状态，否则，熔断器处于半开启状态，在这个状态下，会发起一次请求尝试，这就把原来的 10 分钟后重试调整为了 8 分钟后重试。
                $halfOpen = true;
            }
        }


        try {
            $response = Http::acceptJson()
                ->timeout(10)
                ->get('url');
        } catch (ConnectionException $e) {
            // Increment the failures and do the rest of the circuit
            // breaker work

            // Release to be retried
        }

        if ($response->serverError()) {
            // 检查熔断器
            // 在这次尝试请求失败后，在半开启状态打开的情况下，直接重启熔断器，而如果请求成功，则通过移除缓存键来关闭熔断器。
            if (isset($halfOpen)) {
                Cache::put('circuit:open', 1, 600);

                return $this->release(600);
            }

            // 设置计数器
            if (!Cache::get('failures')) {
                Cache::put('failures', 1, 60);
            } else {
                Cache::increment('failures');
            }
            // 开启熔断器 达到一定次数后开启
            if (Cache::get('failures') > 10) {
                Cache::put('circuit:open', time(), 600);
            }

            return $this->release(600);
        }

        Cache::forget('failures');
        Cache::forget('circuit:open');
    }
}
