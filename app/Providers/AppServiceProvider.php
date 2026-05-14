<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Models\Repositories\Contracts\UsersRepositoryInterface', 'App\Models\Repositories\UsersRepository');
        $this->app->bind('App\Models\Repositories\Contracts\DocumentsRepositoryInterface', 'App\Models\Repositories\DocumentsRepository');
        $this->app->bind('App\Models\Repositories\Contracts\AgreementsRepositoryInterface', 'App\Models\Repositories\AgreementsRepository');
        $this->app->bind('App\Models\Repositories\Contracts\AssignmentsRepositoryInterface', 'App\Models\Repositories\AssignmentsRepository');
        $this->app->bind('App\Models\Repositories\Contracts\BlogRepositoryInterface', 'App\Models\Repositories\BlogRepository');
        $this->app->bind('App\Models\Repositories\Contracts\AcquaintanceRepositoryInterface', 'App\Models\Repositories\AcquaintanceRepository');
        $this->app->bind('App\Models\Repositories\Contracts\SearchRepositoryInterface', 'App\Models\Repositories\SearchRepository');
        $this->app->bind('App\Models\Repositories\Contracts\FilesRepositoryInterface', 'App\Models\Repositories\FilesRepository');
        $this->app->bind('App\Models\Repositories\Contracts\RelationsRepositoryInterface', 'App\Models\Repositories\RelationsRepository');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        date_default_timezone_set('Europe/Moscow');
        if (config('app.env') === 'production') {
            \URL::forceScheme('https');
        };
    }
}
