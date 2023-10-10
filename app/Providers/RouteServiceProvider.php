<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for(
            'api',
            function (Request $request) {
                return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
            }
        );

        RateLimiter::for(
            'global',
            function (Request $request) {
                return Limit::perMinute(1)
                    ->by($request->user()?->id ?: $request->ip())
                    ->response(
                        function (Request $request, array $headers) {
                            return response(
                                'Слишком много запросов',
                                Response::HTTP_TOO_MANY_REQUESTS,
                                $headers
                            );
                        }
                    );
            }
        );

        $this->routes(
            function () {
                Route::middleware('api')
                    ->prefix('api')
                    ->group(base_path('routes/api.php'));

                Route::middleware('web')
                    ->group(base_path('routes/web.php'));
            }
        );
    }

    /**
     *  Configure the rate limiters for the application.
     *
     * @return void
     *
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for(
            'global',
            function (Request $request) {
                return Limit::perMinute(1)
                    ->by($request->user()?->id ?: $request->ip())
                    ->response(
                        function (Request $request, array $headers) {
                            return response(
                                'Слишком много запросов',
                                Response::HTTP_TOO_MANY_REQUESTS,
                                $headers
                            );
                        }
                    );
            }
        );
    }
}
