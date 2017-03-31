<?php

namespace Modules\Blog\Repositories;

use Modules\Core\Repositories\BaseRepository;

/**
 * Interface CategoryRepository
 * @package Modules\Blog\Repositories
 */
interface CategoryRepository extends BaseRepository
{
    public function findByIdInLocales($id);
}
