<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BinarySearchController extends Controller
{
    public function binary_search($nums = [1, 2, 3, 3, 4, 5, 6], $num = 3)
    {
        return $this->binary_search_internal($nums, $num, 0, count($nums) - 1);
    }

    //
//    public function binary_search_internal($nums, $num, $low, $high)
//    {
//        if ($low > $high) {
//            return -1;
//        }
//
//        $mid = floor(($low + $high) / 2);
//        if ($num > $nums[$mid]) {
//            return $this->binary_search_internal($nums, $num, $mid + 1, $high);
//        } elseif ($num < $nums[$mid]) {
//            return $this->binary_search_internal($nums, $num, $low, $mid - 1);
//        } else {
//            return $mid;
//        }
//    }

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
            // 二分法查找
//            return $mid;
            // 二分法查找第一个给定值的元素
//            if ($mid == 0 || $nums[$mid - 1] != $num) {
//                return $mid;
//            } else {
//                return $this->binary_search_internal($nums, $num, $low, $mid - 1);
//            }
            // 二分法查找最后一个给定值的元素
            if ($mid === count($nums) - 1 || $nums[$mid + 1] != $num) {
                return $mid;
            } else {
                return $this->binary_search_internal($nums, $num, $mid + 1, $high);
            }
        }
    }

}
