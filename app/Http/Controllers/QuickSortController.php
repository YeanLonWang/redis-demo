<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuickSortController extends Controller
{
    //

    public function quick_sort($nums = [4, 5, 6, 3, 2, 1])
    {
        if (count($nums) <= 1) {
            return $nums;
        }

        $this->quick_sort_c($nums, 0, count($nums) - 1);
        return $nums;
    }

    public function quick_sort_c(&$nums, $p, $r)
    {
        if ($p >= $r) {
            return;
        }

        $q = $this->partition($nums, $p, $r);
        $this->quick_sort_c($nums, $p, $q - 1);
        $this->quick_sort_c($nums, $q + 1, $r);
    }

    // 寻找 pivot
    public function partition(&$nums, $p, $r)
    {
        $pivot = $nums[$r];
        $i = $p;
        for ($j = $p; $j < $r; $j++) {
            // 原理：将比 $pivot 小的数丢到 [ $p, ... $i-1 ]，剩下的[ $i .. .$j ] 区间都是比 $pivot 大的
            if ($nums[$j] < $pivot) {
                $temp = $nums[$i];
                $nums[$i] = $nums[$j];
                $nums[$j] = $temp;
                $i++;
            }
        }

        // 最后将 $pivot 放到中间，并返回 $i
        $temp = $nums[$i];
        $nums[$i] = $pivot;
        $nums[$r] = $temp;

        return $i;
    }
}
