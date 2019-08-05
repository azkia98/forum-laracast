<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Channel;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {


    }
    
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {

            $channels = Cache::rememberForever('channels', function () {
                return Channel::all();
            });


            $view->with('channels', $channels);
        });
        Validator::extend('spamfree','App\Rules\SpamFree@passes');
    }
}
