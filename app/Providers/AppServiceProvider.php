<?php

namespace App\Providers;

use App\Jobs\UpdateCarsByVpic;
use Illuminate\Console\Scheduling\Schedule;
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
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
        // ...
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

    /**
     * Register jobs.
     *
     * @param  Schedule  $schedule
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function jobs(Schedule $schedule)
    {
        $schedule->job(new UpdateCarsByVpic())->monthly()->at('00:00');
    }
}
