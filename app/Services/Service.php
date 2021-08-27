<?php

namespace App\Services;

class Service
{
    /**
     * @var string
     */
    public $url;

    public function __construct($url = 'https://www.baidu.com')
    {
        $this->url = $url;
    }
}
