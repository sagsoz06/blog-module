<?php

namespace Modules\Blog\Entities;

use Carbon\Carbon;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;
use Modules\Blog\Presenters\CategoryPresenter;

class Category extends Model
{
    use Translatable, PresentableTrait;

    public $translatedAttributes = ['name', 'slug', 'meta_title', 'meta_description'];
    protected $fillable = ['name', 'slug', 'meta_title', 'meta_description', 'sitemap_frequency', 'sitemap_priority', 'updated_at', 'meta_robot_no_index', 'meta_robot_no_follow', 'ordering'];
    protected $table = 'blog__categories';
    protected $presenter = CategoryPresenter::class;

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function update(array $attributes = [], array $options = [])
    {
        $attributes['updated_at'] = Carbon::now();
        return parent::update($attributes, $options);
    }

    public function getRobotsAttribute()
    {
        return $this->meta_robot_no_index.', '.$this->meta_robot_no_follow;
    }

    public function getUrlAttribute()
    {
        return localize_trans_url(locale(), 'blog::routes.category.slug', ['slug'=>$this->slug]);
    }
}
