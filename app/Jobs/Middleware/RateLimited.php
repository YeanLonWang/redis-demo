<?php
/**
 * Notes:
 * User: 1247578853@qq.com
 * DateTime: 2021/11/29 15:15:53
 */

namespace App\Jobs\Middleware;

use Illuminate\Contracts\Redis\LimiterTimeoutException;
use Illuminate\Support\Facades\Redis;

class RateLimited
{
    public function handle($job, $next)
    {
        try {
            Redis::throttle('key')
                ->block(0)->allow(1)->every(5)
                ->then(function () use ($job, $next) {
                    // 锁定
                    $next($job);
                }, function () use ($job) {
                    // 无法获取锁
                    $job->release(5);
                });
        } catch (LimiterTimeoutException $e) {
        }
    }
}
