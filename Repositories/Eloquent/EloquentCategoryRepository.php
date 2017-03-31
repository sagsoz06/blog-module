<?php

namespace Modules\Blog\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Modules\Blog\Repositories\CategoryRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;

class EloquentCategoryRepository extends EloquentBaseRepository implements CategoryRepository
{
    public function findByIdInLocales($id)
    {
        return $this->model->whereHas('translations', function (Builder $q) use ($id) {
            $q->where('category_id', $id);
        })->with('translations', 'posts')->first();
    }

    public function all()
    {
        return $this->model->orderBy('ordering', 'asc')->get();
    }
}
