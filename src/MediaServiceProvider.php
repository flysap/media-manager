<?php

namespace Flysap\Media;

use Cartalyst\Tags\TagsServiceProvider;
use Illuminate\Support\ServiceProvider;
use Flysap\Support;
use Parfumix\Imageonfly\ImageOnFlyServiceProvider;

class MediaServiceProvider extends ServiceProvider {

    public function boot() {
        $this->loadRoutes()
            ->loadConfiguration()
            ->registerMenu();

        $this->publishes([
            __DIR__.'/../configuration' => config_path('yaml/media-manager'),
            __DIR__.'/../migrations/' => database_path('migrations'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->registerDependencies();
    }

    /**
     * Load routes .
     *
     * @return $this
     */
    protected function loadRoutes() {
        /** Register routes . */
        if (! $this->app->routesAreCached())
            require __DIR__.'/../routes.php';

        return $this;
    }

    /**
     * Load configuration .
     *
     * @return $this
     */
    protected function loadConfiguration() {
        Support\set_config_from_yaml(
            __DIR__ . '/../configuration/general.yaml' , 'media-manager'
        );

        Support\merge_yaml_config_from(
            config_path('yaml/media-manager/general.yaml') , 'media-manager'
        );

        return $this;
    }

    /**
     * Register menu .
     *
     */
    protected function registerMenu() {
        $menuManager = app('menu-manager');

        $menuManager->addNamespace(realpath(__DIR__ . '/../'), true);
    }

    /**
     * Register service provider dependencies .
     *
     */
    protected function registerDependencies() {
        $dependencies = [
            ImageOnFlyServiceProvider::class,
            TagsServiceProvider::class,
        ];

        array_walk($dependencies, function($dependency) {
            app()->register($dependency);
        });

        return $this;
    }

}