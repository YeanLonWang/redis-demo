<?php

namespace App\Http\Controllers;

use App\Jobs\Trade;
use Illuminate\Http\Request;

class TradeController extends Controller
{
    //
    public function trade()
    {
        $data = [
            'tid' => date('Y-m-d H:i:s') . uniqid(),
        ];

        $job = new Trade($data);
        $job->dispatch($job)->onQueue('trade');
        return '订单的方法';
    }
}
