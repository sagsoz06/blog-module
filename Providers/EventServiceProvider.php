<?php namespace Modules\Blog\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Blog\Events\PostWasCreated;
use Modules\Blog\Events\PostWasDeleted;
use Modules\Blog\Events\PostWasUpdated;
use Modules\Media\Events\Handlers\HandleMediaStorage;
use Modules\Media\Events\Handlers\RemovePolymorphicLink;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
      PostWasUpdated::class => [
          HandleMediaStorage::class
      ],
      PostWasCreated::class => [
          HandleMediaStorage::class
      ],
      PostWasDeleted::class => [
          RemovePolymorphicLink::class
      ]
    ];
}