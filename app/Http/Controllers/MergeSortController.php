<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MergeSortController extends Controller
{

    // [4,5,6,3,2,1]
    public function merge_sort($nums = [4, 5, 6, 3, 2, 1])
    {
        if (count($nums) <= 1) {
            return $nums;
        }

        $this->merge_sort_c($nums, 0, count($nums) - 1);
        return $nums;
    }

    public function merge_sort_c(&$nums, $p, $r)
    {
        if ($p >= $r) {
            return;
        }

        $q = floor(($p + $r) / 2);
        $this->merge_sort_c($nums, $p, $q);
        $this->merge_sort_c($nums, $q + 1, $r);

        $this->merge($nums, ['start' => $p, 'end' => $q], ['start' => $q + 1, 'end' => $r]);
    }

    //
    public function merge(&$nums, $nums_p, $nums_q)
    {
        //将每个元素拆分成大小为1的部分
        //recursively merge adjacent partitions
        $temp = [];
        $i = $nums_p['start'];
        $j = $nums_q['start'];
        $k = 0;
        //i = 左侧开始项指数 到 右侧最后项指数 的遍历（两端包括）
        while ($i <= $nums_p['end'] && $j <= $nums_q['end']) {
            //如果左侧首值 <= 右侧首值
            if ($nums[$i] <= $nums[$j]) {
                //拷贝左侧首项的值
                $temp[$k++] = $nums[$i++];
            } else {
                //否则： 拷贝右侧部分首值
                $temp[$k++] = $nums[$j++];
            }
        }

        if ($i <= $nums_p['end']) {
            for (; $i <= $nums_p['end']; $i++) {
                $temp[$k++] = $nums[$i];
            }
        }

        if ($j <= $nums_q['end']) {
            for (; $j <= $nums_q['end']; $j++) {
                $temp[$k++] = $nums[$j];
            }
        }

        //将元素拷贝进原来的数组中
        for ($x = 0; $x < $k; $x++) {
            $nums[$nums_p['start'] + $x] = $temp[$x];
        }
    }
}
