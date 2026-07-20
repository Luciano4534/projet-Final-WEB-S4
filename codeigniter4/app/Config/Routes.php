<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Routes par défaut
$routes->get('/', 'WelcomeController::index');

$routes->get('/hello', 'Home::index');
$routes->get('/hello', 'Home::index');

// Authentification client
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::loginPost');
$routes->get('/logout', 'AuthController::logout');

// CRUD Préfixes (côté opérateur)
$routes->get('/prefixes', 'PrefixesController::index');
$routes->get('/prefixes/create', 'PrefixesController::create');
$routes->post('/prefixes/store', 'PrefixesController::store');
$routes->get('/prefixes/edit/(:num)', 'PrefixesController::edit/$1');
$routes->post('/prefixes/update/(:num)', 'PrefixesController::update/$1');
$routes->post('/prefixes/delete/(:num)', 'PrefixesController::delete/$1');

// CRUD Types d'opérations (côté opérateur)
$routes->get('/types-operations', 'TypesOperationsController::index');
$routes->get('/types-operations/create', 'TypesOperationsController::create');
$routes->post('/types-operations/store', 'TypesOperationsController::store');
$routes->get('/types-operations/edit/(:num)', 'TypesOperationsController::edit/$1');
$routes->post('/types-operations/update/(:num)', 'TypesOperationsController::update/$1');
$routes->post('/types-operations/delete/(:num)', 'TypesOperationsController::delete/$1');

// Produits (placeholder existant)
$routes->get('/produits', 'Produits::index');
$routes->get('/produits/(:num)', 'Produits::show/$1');
