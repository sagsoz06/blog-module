<?php

namespace Modules\Blog\Entities;

use Illuminate\Database\Eloquent\Model;

class PostTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['title', 'slug', 'intro', 'content', 'meta_title', 'meta_description', 'og_title', 'og_description', 'og_type'];
    protected $table = 'blog__post_translations';

    public function getUrlAttribute()
    {
        return localize_trans_url($this->locale, 'blog::routes.blog.slug', ['slug'=>$this->slug]);
    }
}
