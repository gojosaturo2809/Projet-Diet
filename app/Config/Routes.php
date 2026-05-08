<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Login::index');
$routes->get('/login', 'Login::index');
$routes->get('/inscription', 'Inscription::index');

$routes->post('/inscription/inscription', 'Inscription::inscription');
