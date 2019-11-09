<?php

namespace Ccbox\AdminWorkorder;

use Illuminate\Support\ServiceProvider;
use Ccbox\AdminWorkorder\Commands\InstallCommand;
use Ccbox\AdminWorkorder\Commands\UpdateCommand;

class AdminWorkorderServiceProvider extends ServiceProvider
{
    protected $commands = [
        InstallCommand::class,
        UpdateCommand::class
    ];

    /**
     * {@inheritdoc}
     */
    public function boot(AdminWorkorder $extension)
    {
        if (! AdminWorkorder::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'admin-workorder');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/ccbox/admin-workorder')],
                'admin-workorder'
            );
        }

        $this->app->booted(function () {
            AdminWorkorder::routes(__DIR__.'/../routes/web.php');
        });
    }
    
    public function register()
    {
        $this->commands($this->commands);
    }
}