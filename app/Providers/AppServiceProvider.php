<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        app()->setLocale('ko');
        \Carbon\Carbon::setLocale(app()->getLocale());

        view()->composer('*', function ($view) {
            $allTags = \Cache::rememberForever('tags.list', function () {
                return \App\Tag::all();
            });
            $currentUser = auth()->user();
            $currentLocale = app()->getLocale();
            $currentUrl = request()->fullUrl();
            $view->with(compact('allTags', 'currentUser', 'currentLocale', 'currentUrl'));
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}