<?php

namespace Modules\Blog\Entities;

use Carbon\Carbon;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;
use Modules\Blog\Presenters\PostPresenter;
use Modules\Core\Traits\NamespacedEntity;
use Modules\Media\Entities\File;
use Modules\Media\Support\Traits\MediaRelation;
use Modules\Tag\Contracts\TaggableInterface;
use Modules\Tag\Traits\TaggableTrait;
use Modules\User\Contracts\Authentication;
use Modules\User\Entities\Sentinel\User;

class Post extends Model implements TaggableInterface
{
    use Translatable, MediaRelation, PresentableTrait, TaggableTrait, NamespacedEntity;

    public $translatedAttributes = ['title', 'slug', 'intro', 'content', 'meta_title', 'meta_description', 'og_title', 'og_description', 'og_type'];
    protected $fillable = ['category_id', 'user_id', 'status', 'title', 'slug', 'intro', 'content', 'meta_title', 'meta_description', 'og_title', 'og_description', 'og_type', 'meta_robot_no_index', 'meta_robot_no_follow', 'sitemap_frequency', 'sitemap_priority', 'created_at', 'updated_at'];
    protected $table = 'blog__posts';
    protected $presenter = PostPresenter::class;
    protected $with = ['category', 'author'];
    protected $casts = [
        'status' => 'int',
    ];
    protected static $entityNamespace = 'asgardcms/blog';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return false|string
     */
    public function getUrlAttribute()
    {
        return localize_trans_url(locale(), 'blog::routes.blog.slug', ['slug'=>$this->slug]);
    }

    /**
     * Get the thumbnail image for the current blog post
     * @return File|string
     */
    public function getThumbnailAttribute()
    {
        if (isset($this->files()->first()->filename)) {
            return url(\Imagy::getThumbnail($this->files()->first()->filename, 'blogThumb'));
        }
        return null;
    }

    /**
     * @return string
     */
    public function getRobotsAttribute()
    {
        return $this->meta_robot_no_index.', '.$this->meta_robot_no_follow;
    }

    /**
     * Check if the post is in draft
     * @param Builder $query
     * @return bool
     */
    public function scopeDraft(Builder $query)
    {
        return (bool)$query->whereStatus(Status::DRAFT);
    }

    /**
     * Check if the post is pending review
     * @param Builder $query
     * @return bool
     */
    public function scopePending(Builder $query)
    {
        return (bool)$query->whereStatus(Status::PENDING);
    }

    /**
     * Check if the post is published
     * @param Builder $query
     * @return bool
     */
    public function scopePublished(Builder $query)
    {
        return (bool)$query->whereStatus(Status::PUBLISHED);
    }

    /**
     * Check if the post is unpublish
     * @param Builder $query
     * @return bool
     */
    public function scopeUnpublished(Builder $query)
    {
        return (bool)$query->whereStatus(Status::UNPUBLISHED);
    }

    /**
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeMatch($query, $value)
    {
        return $query->whereHas('translations', function (Builder $q) use ($value) {
            $q->whereRaw("MATCH(title, description) AGAINST(? IN BOOLEAN MODE)", $this->fullTextWildcards($value));
        })->with(['translations'])->whereStatus(Status::PUBLISHED);
    }

    protected function fullTextWildcards($term)
    {
        // removing symbols used by MySQL
        $term = preg_replace('/[^\p{L}\p{N}_]+/u', ' ', $term);
        $words = explode(' ', $term);
        foreach ($words as $key => $word) {
            /*
             * applying + operator (required word) only big words
             * because smaller ones are not indexed by mysql
             */
            if (strlen($word) >= 3) {
                $words[$key] = '+' . $word . '*';
            }
        }

        $searchTerm = implode(' ', $words);

        return $searchTerm;
    }

    /**
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        #i: Convert array to dot notation
        $config = implode('.', ['asgard.blog.config.post.relations', $method]);

        #i: Relation method resolver
        if (config()->has($config)) {
            $function = config()->get($config);

            return $function($this);
        }

        #i: No relation found, return the call to parent (Eloquent) to handle it.
        return parent::__call($method, $parameters);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function($post){
            $post->user_id = app(Authentication::class)->user()->id;
        });

        static::saving(function($post){
            $post->updated_at = Carbon::now();
        });
    }
}
