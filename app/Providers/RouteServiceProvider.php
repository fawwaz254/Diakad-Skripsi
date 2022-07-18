<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {

            $this->mapApiRoutes();

            $this->mapWebRoutes();
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::group([
            'middleware' => 'web',
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/web.php');
            require base_path('routes/administrator/web.php');
            require base_path('routes/akademik/web.php');
            require base_path('routes/alumni/web.php');
            require base_path('routes/bimbingan-konseling/web.php');
            require base_path('routes/guru/web.php');
            require base_path('routes/humas/web.php');
            require base_path('routes/kesiswaan/web.php');
            require base_path('routes/keuangan/web.php');
            require base_path('routes/pelatih-ekskul/web.php');
            require base_path('routes/pendidikan/web.php');
            require base_path('routes/ppdb/admin.php');
            require base_path('routes/rapor-buku-induk/web.php');
            require base_path('routes/sarana-prasarana/web.php');
            require base_path('routes/sekretariat/web.php');
            require base_path('routes/siswa/web.php');
            require base_path('routes/sumber-daya/web.php');
            require base_path('routes/tendik/web.php');
            require base_path('routes/wali-murid/web.php');
        });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }
}
