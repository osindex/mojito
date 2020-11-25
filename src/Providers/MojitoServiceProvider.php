<?php

namespace Moell\Mojito\Providers;

use Illuminate\Support\ServiceProvider;
use Moell\Mojito\Console\InstallCommand;
use Moell\Mojito\Console\UninstallCommand;

class MojitoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {

            $this->commands([
                InstallCommand::class,
                UninstallCommand::class,
            ]);

            $this->publishes([
                __DIR__ . '/../../config/mojito.php' => config_path('mojito.php'),
            ], 'config');

            $path = version_compare(app()->version(), '5.7.0', '>=')
            ? base_path('resources/js')
            : base_path('resources/assets/js');

            $this->publishes([
                __DIR__ . '/../../resources' => $path,
            ]);

            $this->publishes([
                __DIR__ . '/../../resources' => $path,
            ], 'views');
            $this->publishes([
                __DIR__ . '/../../lang' => base_path('resources/lang'),
            ], 'lang');
            $this->publishes([
                __DIR__ . '/../../views' => base_path('resources/views'),
            ], 'views');
        }

        $this->registerRouter();
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

    /**
     * 注册路由
     *
     * @author moell
     */
    private function registerRouter()
    {
        if (strpos($this->app->version(), 'Lumen') === false && !$this->app->routesAreCached()) {
            app('router')->middleware('api')->group(__DIR__ . '/../routes.php');
        } else {
            require __DIR__ . '/../routes.php';
        }
    }
}
