<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Login::index');
$routes->get('/login', 'Login::index');
$routes->post('/login', 'Login::login');
$routes->post('/login/login', 'Login::login');
$routes->get('/dashboard', 'Dashboard::index');
$routes->get('/logout', 'Login::logout');
$routes->get('/inscription', 'Inscription::index');
$routes->post('/inscription/inscription', 'Inscription::inscription');

$routes->get('/wallet', 'WalletController::wallet');
$routes->get('/wallet/recharge', 'WalletController::recharge');
$routes->post('/wallet/add-money', 'WalletController::addMoney');

$routes->get('/gold', 'GoldController::index');
$routes->post('/gold/activate', 'GoldController::activateGold');
$routes->post('/inscription/sante', 'Inscription::sante');
$routes->post('/inscription/inscription', 'Inscription::inscription');
$routes->get('/inscription/objectifs', 'ObjectifController::index');

// Objectifs routes
$routes->group('objectifs', static function ($routes) {
    $routes->get('/', 'ObjectifController::index');
    $routes->post('selectionner', 'ObjectifController::selectionner');
});
