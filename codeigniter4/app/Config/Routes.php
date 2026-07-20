<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Page d'accueil
$routes->get('/', 'WelcomeController::index');

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

// CRUD Barèmes (côté opérateur)
$routes->get('/baremes', 'BaremesController::index');
$routes->get('/baremes/create', 'BaremesController::create');
$routes->post('/baremes/store', 'BaremesController::store');
$routes->get('/baremes/edit/(:num)', 'BaremesController::edit/$1');
$routes->post('/baremes/update/(:num)', 'BaremesController::update/$1');
$routes->post('/baremes/delete/(:num)', 'BaremesController::delete/$1');

// Dashboard (côté opérateur)
$routes->get('/dashboard', 'DashboardController::index');
$routes->get('/dashboard/clients', 'DashboardController::clients');
$routes->get('/dashboard/gains', 'DashboardController::gains');

// Interface Client
$routes->get('/client/solde', 'ClientController::solde');
$routes->get('/client/depot', 'ClientController::depot');
$routes->post('/client/depot', 'ClientController::depotPost');
$routes->get('/client/retrait', 'ClientController::retrait');
$routes->post('/client/retrait', 'ClientController::retraitPost');
$routes->get('/client/transfert', 'ClientController::transfert');
$routes->post('/client/transfert', 'ClientController::transfertPost');
$routes->get('/client/transfert-multiple', 'ClientController::transfertMultiple');
$routes->post('/client/transfert-multiple', 'ClientController::transfertMultiplePost');
$routes->get('/client/historique', 'ClientController::historique');
