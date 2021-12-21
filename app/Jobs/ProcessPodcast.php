<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ProcessPodcast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 发送 Webhook
        $response = Http::timeout(5)->post('https://www.baidu.com');
        // 请求失败后重试
        if ($response->failed()) {
            $this->release(
                now()->addMinutes(15 * $this->attempts())
            );
        }
        Cache::lock('');
    }

    // 配置任务过期
    public $tries = 0;

    // 配置任务延迟
    public $backoff = 11;

    public function retryUntil()
    {
        return now()->addDay();
    }

    // 处理任务失败
    public function failed()
    {

    }
}
