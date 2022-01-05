<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RedisLockController extends Controller
{
    // 分布式锁
    public function index()
    {
        // 使用
        $block = Cache::lock('winner', 10); // 返回一个对象 RedisLock
        if ($block->get()) {
            echo '执行业务逻辑';
            $block->release();  // 释放锁 分布式下注意误删
            return '执行成功';
        }
    }

}
