<?php

namespace TMFW\Support;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;
use TMFW\Support\Filters\Email\EventTemplate;

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
        $this->filterEmailTemplate();
    }

    protected function filterEmailTemplate(){
        $this->app->bind('GetEmailTemplate', function ($app) {
            return new EventTemplate();
        });

        $this->app->bind('TMFW\Contracts\Filters\EmailTemplate', 'TMFW\Support\Filters\Email\EventTemplate');
    }

    protected function mapWebRoutes(Router $router){
        $router->group(['middleware' => 'web'], function($router){
            require_once __DIR__.'/routes.php';
        });
    }

}
