<?php

namespace Salmanbe\Patternize;

use Illuminate\Support\ServiceProvider;

class PatternizeServiceProvider extends ServiceProvider {

    public function boot() {

        $this->publishes([
            __DIR__ . '/config.php' => config_path('patternize.php'),
        ]);
    }

    public function register() {

        $this->app->bind('patternize', function($app) {
            return new FileName($app);
        });

        config(['config/patternize.php']);
    }

}
