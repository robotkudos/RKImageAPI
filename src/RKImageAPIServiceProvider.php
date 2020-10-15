<?php

namespace RobotKudos\RKImageAPI;
use Illuminate\Support\ServiceProvider;

class RKImageAPIServiceProvider extends ServiceProvider {
    public function boot() {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations/2020_08_12_111145_create_images_table.php');
        $this->loadRoutesFrom(__DIR__.'/routes/images.php');


        $this->publishes([__DIR__ . '/config/rkimageapi.php' => config_path('rkimageapi.php')]);
    }
}
