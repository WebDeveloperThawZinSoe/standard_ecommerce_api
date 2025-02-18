<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // if (Schema::hasTable('translations')) {
        //     App::singleton('translations', function () {
        //         return Cache::rememberForever('translations', function () {
        //             return DB::table('translations')
        //                 ->pluck('value', 'key')
        //                 ->toArray();
        //         });
        //     });
        // }

        // if (session()->has('locale')) {
        //     app()->setLocale(session('locale'));
        // }
    }
}
