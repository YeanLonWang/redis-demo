<?php

namespace App\Jobs\Middleware;

class RateLimitingJobMiddleware
{
    public function handle($job, $next)
    {
        Redis::throttle('job-limiter')
            ->allow(5)
            ->every(60)
            ->then(function () use ($job, $next) {
                $next($job);
            }, function () use ($job) {
                $job->release(60);
            });
        
    }
}
