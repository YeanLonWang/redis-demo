<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BinarySearchController extends Controller
{
    public function binary_search($nums = [1, 2, 3, 4, 5, 6, 7, 8], $num = 5)
    {
        return $this->binary_search_internal($nums, $num, 0, count($nums) - 1);
    }

    //
    public function binary_search_internal($nums, $num, $low, $high)
    {
        if ($low > $high) {
            return -1;
        }

        $mid = floor(($low + $high) / 2);
        if ($num > $nums[$mid]) {
            return $this->binary_search_internal($nums, $num, $mid + 1, $high);
        } elseif ($num < $nums[$mid]) {
            return $this->binary_search_internal($nums, $num, $low, $mid - 1);
        } else {
            return $mid;
        }
    }

}
