<?php namespace Modules\Blog\Entities\Helpers;


class OgType
{
    const WEBSITE = 'website';
    const PRODUCT = 'product';
    const ARTICLE = 'article';

    private $types = [];

    public function __construct()
    {
        $this->types = [
          self::ARTICLE => trans('blog::post.form.og_types.article'),
          self::PRODUCT => trans('blog::post.form.og_types.product'),
          self::WEBSITE => trans('blog::post.form.og_types.website')
        ];
    }

    public function lists()
    {
        return $this->types;
    }

    public function get($typeId)
    {
        if (isset($this->types[$typeId])) {
            return $this->types[$typeId];
        }

        return $this->types[self::WEBSITE];
    }
}