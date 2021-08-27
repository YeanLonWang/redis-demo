<?php

namespace App\Jobs;

use App\Services\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $queue = 'Service';

    public Service $service;
    public array $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Service $service, array $data)
    {
        //
        $this->service = $service;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 基于 HTTP 请求发送响应给调用方
        Http::timeout(5)->post($this->service->url, $this->data);
    }
}
