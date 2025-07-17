<?php
/**
 * Routes configuration.
 *
 * In this file, you set up routes to your controllers and their actions.
 */

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\Router;
use Cake\Routing\RouteBuilder;

Router::defaultRouteClass(DashedRoute::class);

// Nueva ruta que añadimos para nuestra acción tagged
Router::scope(
    '/bookmarks',
    ['controller' => 'Bookmarks'],
    function (RouteBuilder $routes) {
        $routes->connect('/tagged/*', ['action' => 'tags']);
    }
);

return function (RouteBuilder $routes): void {
    $routes->setRouteClass(DashedRoute::class);

    $routes->scope('/', function (RouteBuilder $builder): void {
        // Ruta al home
        $builder->connect('/', [
            'controller' => 'Pages',
            'action' => 'display',
            'home'
        ]);

        // Ruta para otras páginas
        $builder->connect('/pages/*', [
            'controller' => 'Pages',
            'action' => 'display'
        ]);

        // Rutas automáticas convencionales
        $builder->fallbacks();
    });
};