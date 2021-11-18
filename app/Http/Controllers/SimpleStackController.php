<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SimpleStackController extends Controller
{
    //
    private $_stack = [];
    private $_size = 0;

    public function __construct($size = 10)
    {
        $this->_size = $size;
    }

    // 获取栈顶元素
    public function pop()
    {
        // 空栈
        if (count($this->_stack) == 0) {
            return false;
        }
        return array_pop($this->_stack);
    }

    // 推送元素到栈顶
    public function push($value)
    {
        // 满栈
        if (count($this->_stack) == $this->_size) {
            return false;
        }
        array_push($this->_stack, $value);
        return true;
    }

    public function isEmpty()
    {
        return current($this->_stack) == false;
    }

    public function size()
    {
        return count($this->_stack);
    }

}
