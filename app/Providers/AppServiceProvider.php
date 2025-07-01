<?php

namespace App\Providers;

use App\Models\Pengguna;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // if ($this->app->environment() !== 'production') {
        //     $this->app->register(\Way\Generators\GeneratorsServiceProvider::class);
        //     $this->app->register(\Xethron\MigrationsGenerator\MigrationsGeneratorServiceProvider::class);
        // }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');

        Gate::define('viewPulse', function (Pengguna $pengguna) {
            return $pengguna->username == 'admin';
        });
    }
}
