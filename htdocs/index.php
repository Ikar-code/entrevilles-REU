<?php
/**
 * Point d'entrée unique de l'application (front controller).
 * Toutes les requêtes sont redirigées ici par .htaccess.
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/Core/autoload.php';
require_once __DIR__ . '/app/routes.php';

Router::traiter();
