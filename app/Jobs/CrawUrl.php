<?php

namespace App\Jobs;

use App\Models\CrawlSource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CrawUrl implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public CrawlSource $crawlSource;

    /**
     * Create a new job instance.
     *
     * @param CrawlSource $crawlSource
     */
    public function __construct(CrawlSource $crawlSource)
    {
        //
        $this->crawlSource = $crawlSource;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //模拟爬取 URL 操作
        sleep(1);
        $this->crawlSource->status = 1;
        $this->crawlSource->save();
    }
}
