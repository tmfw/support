<?php

namespace TMFW\Support;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;
use MCMIS\Support\Filters\Sample\DataManipulation;

class Register
{

    protected $app;

    /**
     * Bootstrap script
     *
     * @param Application $app
     * @return void
     */
    public function bootstrap(Application $app){
        $this->app = $app;

        $this->registerFilters();

        $this->mapWebRoutes($app->make(Router::class));
    }

    protected function registerFilters(){
        $this->filterDataManipulation();
    }

    protected function filterDataManipulation(){
        $this->app->bind('GetDataManipulator', function ($app) {
            return new DataManipulation();
        });

        $this->app->bind('TMFW\Contracts\Filters\DataManipulator', 'TMFW\Support\Filters\Sample\DataManipulation');
    }

    protected function mapWebRoutes(Router $router){
        /* Inject routes dynamically */
        $router->group(['middleware' => 'web'], function($router){
            require_once __DIR__.'/routes.php';
        });
    }

}
