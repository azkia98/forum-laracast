<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Channel;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        View::composer('*', function ($view) {

            $channels = Cache::rememberForever('channels', function () {
                return Channel::all();
            });


            $view->with('channels', $channels);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
