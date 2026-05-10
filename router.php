<?php
/**
 * Router pour le serveur de développement PHP built-in (php -S)
 * Lance la commande: php -S localhost:8001 router.php (sans -t public)
 */

if (php_sapi_name() === 'cli-server') {
    // Ajoute le chemin public/ au REQUEST_URI pour la résolution
    if ($_SERVER['REQUEST_URI'] === '/') {
        $_SERVER['REQUEST_URI'] = '/index.php';
    }
    
    // Vérifie si c'est un fichier statique dans public/
    $path = __DIR__ . '/public' . $_SERVER['REQUEST_URI'];
    if (is_file($path)) {
        // C'est un fichier stattique (CSS, JS, images, etc.), le serveur le sert
        return false;
    }
    
    // Sinon, router tout vers index.php
    $_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/public/index.php';
    $_SERVER['SCRIPT_NAME'] = '/index.php';
}

// Charge CodeIgniter
require __DIR__ . '/public/index.php';
