<?php

namespace App\Providers;

use App\Models\Config;
use App\Services\ConfigService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
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
        DB::listen(function ($query) {
            $tmp = str_replace('?', '"' . '%s' . '"', $query->sql);
            $tmp = vsprintf($tmp, $query->bindings);
            $tmp = str_replace("\\", "", $tmp);
            Log::info($tmp . "\n\n\t");
        });
        ConfigService::load();
        Schema::defaultStringLength(191);

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
