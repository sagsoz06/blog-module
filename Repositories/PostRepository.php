<?php

namespace Modules\Blog\Repositories;

use Modules\Core\Repositories\BaseRepository;

interface PostRepository extends BaseRepository
{
    /**
     * @param int $amount
     * @return mixed
     */
    public function latest($amount = 5);

    /**
     * Get the previous post of the given post
     * @param object $post
     * @return object
     */
    public function getPreviousOf($post);

    /**
     * Get the next post of the given post
     * @param object $post
     * @return object
     */
    public function getNextOf($post);

    /**
     * @param $tag
     * @return mixed
     */
    public function findByTag($tag);

    /**
     * @param $id
     * @param $locale
     * @return object
     */
    public function findByIdInLocales($id);

    /**
     * @param $lang
     * @param $per_page
     * @return mixed
     */
    public function allTranslatedInPaginate($lang, $per_page);

    /**
     * @param $tag
     * @param $per_page
     * @return mixed
     */
    public function findByTagPaginate($tag, $per_page);

    /**
     * @param int $amount
     * @return mixed
     */
    public function popular($amount=5);

    /**
     * @param $query
     * @param $per_page
     * @return mixed
     */
    public function search($query, $per_page);
}
