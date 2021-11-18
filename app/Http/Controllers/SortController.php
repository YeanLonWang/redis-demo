<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SortController extends Controller
{
    // 冒泡排序  [4,5,6,1,2,3]
    public function bubble_sort($nums = [4, 5, 6, 1, 2, 3])
    {
        if (count($nums) <= 1) {
            return $nums;
        }
        for ($i = 0; $i < count($nums); $i++) {
            $flag = false;
            for ($j = 0; $j < count($nums) - $i - 1; $j++) {
                if ($nums[$j] > $nums[$j + 1]) {
                    $temp = $nums[$j];
                    $nums[$j] = $nums[$j + 1];
                    $nums[$j + 1] = $temp;
                    $flag = true;
                }
            }
            if (!$flag) {
                break;
            }
        }
        return $nums;
    }

    // 插入排序  [4,5,6,1,2,3]
    public function insertion_sort($nums = [4, 5, 6, 3, 2, 1])
    {
        if (count($nums) <= 1) {
            return $nums;
        }
        for ($i = 0; $i < count($nums); $i++) {
            //将第一个元素标记为已排序
            $value = $nums[$i];
            $j = $i - 1;
            //遍历每个没有排序过的元素

            //“提取” 元素 X

            //i = 最后排序过元素的指数 到 0 的遍历
            for (; $j >= 0; $j--) {
                //如果现在排序过的元素 > 提取的元素
                if ($nums[$j] > $value) {
                    //将排序过的元素向右移一格
                    $nums[$j + 1] = $nums[$j];
                } else {
                    break;
                }
            }
            //否则：插入提取的元素
            $nums[$j + 1] = $value;
        }
        return $nums;
    }

    // 选择排序  [4,5,6,1,2,3]
    public function selection_sort($nums = [4, 5, 6, 3, 2, 1])
    {
        if (count($nums) <= 1) {
            return $nums;
        }
        for ($i = 0; $i < count($nums); $i++) {
            //重复（元素个数-1）次
            //把第一个没有排序过的元素设置为最小值
            $min = $i;
            //遍历每个没有排序过的元素
            for ($j = $i + 1; $j < count($nums); $j++) {
                //如果元素 < 现在的最小值
                if ($nums[$j] < $nums[$min]) {
                    //将此元素设置成为新的最小值
                    $min = $j;
                }
            }
            //将最小值和第一个没有排序过的位置交换
            if ($min != $i) {
                $temp = $nums[$i];
                $nums[$i] = $nums[$min];
                $nums[$min] = $temp;
            }
        }
        return $nums;
    }
}
