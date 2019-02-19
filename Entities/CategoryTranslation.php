<?php

namespace Modules\Blog\Entities;

use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'slug', 'meta_title', 'meta_description'];
    protected $table = 'blog__category_translations';

    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        return localize_trans_url($this->locale, 'blog::routes.category.slug', ['slug'=>$this->slug]);
    }
}
