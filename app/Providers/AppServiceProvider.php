<?php

namespace App\Providers;

use App\Faker\FakerImageProvider;
use App\Http\Kernel;
use Carbon\CarbonInterval;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            Generator::class,
            function () {
                $faker = Factory::create();
                $faker->addProvider(new FakerImageProvider($faker));

                return $faker;
            }
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::shouldBeStrict(!app()->isProduction());

        if (app()->isProduction()) {
            DB::listen(
                function ($query) {
//                     $query->sql;
//                     $query->bindings;
//                     $query->time;

//                    if ($query->time > 100) {
//                      logger()
//                          ->channel('telegram')
//                          ->debug('query logger than 1s:' .$query->sql, $query->bindings);
//                    }
                }
            );

            app(Kernel::class)->whenRequestLifecycleIsLongerThan(
                CarbonInterval::seconds(4),
                function () {
//                logger()
//                    ->channel('telegram')
//                    ->debug('whenRequestLifecycleIsLongerThan: ' . request()->url());
                }
            );
        }
    }
}
