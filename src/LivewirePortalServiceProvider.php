<?php

namespace Jeffochoa\LivewirePortal;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Jeffochoa\LivewirePortal\Commands\LivewirePortalCommand;
use Livewire\Component;

class LivewirePortalServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/livewire-portals.php' => config_path('livewire-portals.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../resources/views' => base_path('resources/views/vendor/livewire-portals'),
            ], 'views');

            $this->commands([
                LivewirePortalCommand::class,
            ]);
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'livewire-portals');
        Blade::include('livewire-portals::portal-scripts', 'livewirePortalScripts');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/livewire-portals.php', 'livewire-portals');

        Component::macro('openPortal', function (string $portal, string $component, array $data = []) {
            $content = view('livewire-portals::portal-view', [
                'layout' => 'livewire-portals::portal-layout',
                'section' => 'content',
                'component' => $component,
                'componentParameters' => $data,
            ])->render();

            $this->dispatch(
                event: 'portal-open',
                content: $content,
                portal: $portal
            );
        });
    }
}
