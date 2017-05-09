<?php

namespace Modules\Blog\Repositories\Cache;

use Modules\Blog\Repositories\Collection;
use Modules\Blog\Repositories\PostRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CachePostDecorator extends BaseCacheDecorator implements PostRepository
{
    public function __construct(PostRepository $post)
    {
        parent::__construct();
        $this->entityName = 'posts';
        $this->repository = $post;
    }

    /**
     * Return the latest x blog posts
     * @param int $amount
     * @return Collection
     */
    public function latest($amount = 5)
    {
        return $this->cache
            ->tags([$this->entityName, 'global'])
            ->remember("{$this->locale}.{$this->entityName}.latest.{$amount}", $this->cacheTime,
                function () use ($amount) {
                    return $this->repository->latest($amount);
                }
            );
    }

    /**
     * Get the previous post of the given post
     * @param object $post
     * @return object
     */
    public function getPreviousOf($post)
    {
        $postId = $post->id;

        return $this->cache
            ->tags([$this->entityName, 'global'])
            ->remember("{$this->locale}.{$this->entityName}.getPreviousOf.{$postId}", $this->cacheTime,
                function () use ($post) {
                    return $this->repository->getPreviousOf($post);
                }
            );
    }

    /**
     * Get the next post of the given post
     * @param object $post
     * @return object
     */
    public function getNextOf($post)
    {
        $postId = $post->id;

        return $this->cache
            ->tags([$this->entityName, 'global'])
            ->remember("{$this->locale}.{$this->entityName}.getNextOf.{$postId}", $this->cacheTime,
                function () use ($post) {
                    return $this->repository->getNextOf($post);
                }
            );
    }

    /**
     * @param $tag
     * @return mixed
     */
    public function findByTag($tag)
    {
        return $this->cache
            ->tags([$this->entityName, 'global'])
            ->remember("{$this->locale}.{$this->entityName}.findByTag.{$tag}", $this->cacheTime,
                function () use ($tag) {
                    return $this->repository->findByTag($tag);
                }
            );
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findByIdInLocales($id)
    {
        return $this->cache
            ->tags([$this->entityName, 'global'])
            ->remember("{$this->locale}.{$this->entityName}.findByIdInLocales.{$id}", $this->cacheTime,
                function () use ($id) {
                    return $this->repository->findByIdInLocales($id);
                }
            );
    }

    /**
     * @param $lang
     * @param $per_page
     * @return mixed
     */
    public function allTranslatedInPaginate($lang, $per_page)
    {
        $page = \Request::has('page') ? \Request::query('page') : 1;
        return $this->cache
            ->tags($this->entityName, 'global')
            ->remember("{$this->locale}.{$this->entityName}.allTranslatedInPaginate.{$lang}.{$per_page}.{$page}", $this->cacheTime,
                function () use ($lang, $per_page) {
                    return $this->repository->allTranslatedInPaginate($lang, $per_page);
                }
            );
    }

    /**
     * @param $tag
     * @param $per_page
     * @return mixed
     */
    public function findByTagPaginate($tag, $per_page)
    {
        $page = \Request::has('page') ? \Request::query('page') : 1;
        return $this->cache
            ->tags($this->entityName, 'global')
            ->remember("{$this->locale}.{$this->entityName}.findByTagPaginate.{$tag}.{$per_page}.{$page}", $this->cacheTime,
                function () use ($tag, $per_page) {
                    return $this->repository->findByTagPaginate($tag, $per_page);
                }
            );
    }

    public function popular($amount = 5)
    {
        return $this->cache
            ->tags([$this->entityName, 'global'])
            ->remember("{$this->locale}.{$this->entityName}.popular.{$amount}", $this->cacheTime,
                function () use ($amount) {
                    return $this->repository->popular($amount);
                }
            );
    }

    /**
     * @param $query
     * @param $per_page
     * @return mixed
     */
    public function search($query, $per_page)
    {
        $page = \Request::has('page') ? \Request::query('page') : 1;
        return $this->cache
            ->tags($this->entityName, 'global')
            ->remember("{$this->locale}.{$this->entityName}.search.{$query}.{$per_page}.{$page}", $this->cacheTime,
                function () use ($query, $per_page) {
                    return $this->repository->search($query, $per_page);
                }
            );
    }

    public function archive()
    {
        return $this->cache
            ->tags([$this->entityName, 'global'])
            ->remember("{$this->locale}.{$this->entityName}.archive", $this->cacheTime,
                function () {
                    return $this->repository->archive();
                }
            );
    }
}
