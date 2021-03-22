<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \App\Repositories\UserRepositoryInterface::class,
            \App\Repositories\UserRepository::class
        );
        $this->app->bind(
            \App\Repositories\PostRepositoryInterface::class,
            \App\Repositories\PostRepository::class
        );
        $this->app->bind(
            \App\Repositories\ImageRepositoryInterface::class,
            \App\Repositories\ImageRepository::class
        );
        $this->app->bind(
            \App\Repositories\TagRepositoryInterface::class,
            \App\Repositories\TagRepository::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
