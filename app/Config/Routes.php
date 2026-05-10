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

// Registration wizard routes (AuthController)
$routes->get('/register', 'AuthController::registerStep1');
$routes->get('/register/step1', 'AuthController::registerStep1');
$routes->post('/register/step1', 'AuthController::registerStep1');
$routes->get('/register/step2', 'AuthController::registerStep2');
$routes->post('/register/step2', 'AuthController::registerStep2');

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

    $routes->get('getDetailsForm', 'ObjectifController::getDetailsForm');

    // 3. Traitement final du formulaire (POST)
    // URL : localhost:8080/objectifs/selectionner
    $routes->post('selectionner', 'ObjectifController::selectionner');
});

// Régimes: impression et souscription
$routes->get('regimes/print', 'RegimeController::printable');
$routes->get('regimes/download-pdf', 'RegimeController::downloadPdf');
$routes->post('regimes/souscrire', 'RegimeController::souscrire');
