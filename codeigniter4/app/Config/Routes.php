<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/hello', 'Home::index');
$routes->get('/produits', 'Produits::index');
$routes->get('/produits/(:num)', 'Produits::show/$1');

