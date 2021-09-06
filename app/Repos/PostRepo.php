<?php

namespace App\Repos;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class PostRepo
{
    protected Post $post;
    private $trendingPostsKey;

    public function __construct(Post $post, $trendingPostsKey = 'popular_posts')
    {
        $this->post = $post;
        $this->trendingPostsKey = $trendingPostsKey;
    }

    public function getById(int $id, array $columns = ['*'])
    {
//        $cacheKey = 'post_' . $id;
//        if (Redis::exists($cacheKey)) {
//            return unserialize(Redis::get($cacheKey));
//        }
//        $post = $this->post->select($columns)->find($id);
//        if (!$post) {
//            return null;
//        }
//
//        Redis::setex($cacheKey, 1 * 60 * 60, serialize($post));
//
//        return $post;
        $cacheKey = 'post_' . $id;
        return Cache::remember($cacheKey, 1 * 60 * 60, function () use ($id, $columns) {
            return $this->post->select($columns)->find($id);
        });
    }

    public function getByManyId(array $ids, array $columns = ['*'], callable $callback = null)
    {
        $query = $this->post->select($columns)->whereIn('id', $ids);
        if ($query) {
            $query = $callback($query);

        }
        return $query->get();
    }

    public function addViews(Post $post)
    {
//        $post->increment('views');
//        if ($post->save()) {
//            Redis::zincrby('popular_posts', 1, $post->id);
//        }
//        return $post->views;
        // 推送消息数据到队列，通过一部进程处理数据库更新
        Redis::rpush('post-views-increment', $post->id);
        return ++$post->views;
    }

    public function trending($num = 10)
    {
//        $cacheKey = $this->trendingPostsKey . '_' . $num;
//        if (Redis::exists($cacheKey)) {
//            return unserialize(Redis::get($cacheKey));
//        }
//
//        $postIds = Redis::zrevrange($this->trendingPostsKey, 0, $num - 1);
//        if (!$postIds) {
//            return null;
//        }
//        $idsStr = implode(',', $postIds);
//        $posts = $this->getByManyId($postIds, ['*'], function ($query) use ($idsStr) {
//            return $query->orderByRaw('field(`id`, ' . $idsStr . ')');
//        });
//        Redis::setex($cacheKey, 10 * 60, serialize($posts));
//        return $posts;
        $cacheKey = $this->trendingPostsKey . '_' . $num;
        return Cache::remember($cacheKey, 10 * 60, function () use ($num) {
            $postIds = Redis::zrevrange($this->trendingPostsKey, 0, $num - 1);
            if ($postIds) {
                $idsStr = implode(',', $postIds);
                return $this->getByManyId($postIds, ['*'], function ($query) use ($idsStr) {
                    return $query->orderByRaw('field(`id`,' . $idsStr . ')');
                });
            }
        });
    }
}
