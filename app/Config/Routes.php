<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Login::index');
$routes->get('/login', 'Login::index');
$routes->get('/inscription', 'Inscription::index');
$routes->post('/inscription/inscription', 'Inscription::inscription');

$routes->get('/wallet', 'WalletController::wallet');
$routes->get('/wallet/recharge', 'WalletController::recharge');
$routes->post('/wallet/add-money', 'WalletController::addMoney');

$routes->get('/gold', 'GoldController::index');
$routes->post('/gold/activate', 'GoldController::activateGold');
