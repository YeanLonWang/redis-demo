<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/connection', function () {
    dd(\Illuminate\Support\Facades\Redis::connection());
});

Route::get('/site_visits', function () {
    return '网站全局访问量' . \Illuminate\Support\Facades\Redis::get('site_total_visits');
});

Route::get('/posts/popular', [\App\Http\Controllers\PostController::class, 'popular']);
Route::get('/posts/{id}', [\App\Http\Controllers\PostController::class, 'show'])->where('id', '[0-9]+');
Route::get('/posts/create', [\App\Http\Controllers\PostController::class, 'create']);
Route::post('/posts/store', [\App\Http\Controllers\PostController::class, 'store']);


Route::get('/collection', [\App\Http\Controllers\CollectionController::class, 'collection']);
Route::get('/collection/page', [\App\Http\Controllers\CollectionController::class, 'paginate']);

Route::get('/show-post/{id}', '\App\Http\Controllers\PostShowController');

Route::get('/custom-order', function () {
    return view('custom_order.blade_order');
});

Route::get('/throttle-test', function () {
    return '111';
})->middleware('throttle:10,1');

Route::get('/errors/{code}', function ($code = 500) {
    return abort($code);
});

//获取数据
Route::get('/data-get', function () {
    $arr = [
        0 =>
            ['user_id' => '用户id1', 'created_at' => '时间戳1', 'product' => (object)[
                'id' => 1,
                'name' => 'learn php',
                'price' => 10
            ], 'total_price' => 30],
        1 =>
            ['user_id' => '用户id2', 'created_at' => '时间戳2', 'product' => (object)[
                'id' => 2,
                'name' => 'learn laravel',
                'price' => 20
            ], 'total_price' => 40],
        2 => ['user_id' => '用户id3', 'created_at' => '时间戳3', 'product' => (object)[
            'id' => 3,
            'name' => 'learn go',
            'price' => 30
        ], 'total_price' => 60],
    ];
    $names = data_get($arr, '*.product.name');
    dd($names);
});

Route::get('/bubble', [\App\Http\Controllers\SortController::class, 'bubble_sort']);

Route::get('/insertion', [\App\Http\Controllers\SortController::class, 'insertion_sort']);

Route::get('/selection', [\App\Http\Controllers\SortController::class, 'selection_sort']);

Route::get('/merge_sort', [\App\Http\Controllers\MergeSortController::class, 'merge_sort']);

Route::get('/quick_sort', [\App\Http\Controllers\QuickSortController::class, 'quick_sort']);

Route::get('/binary_search', [\App\Http\Controllers\BinarySearchController::class, 'binary_search']);
