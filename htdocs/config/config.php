<?php
/**
 * Charge le fichier .env (s'il existe) puis démarre la session.
 * Aucune dépendance externe (pas de composer) : on parse le .env à la main.
 */

$envPath = __DIR__ . '/../.env';

if (file_exists($envPath)) {
    $lignes = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lignes as $ligne) {
        if (str_starts_with(trim($ligne), '#')) {
            continue; // commentaire
        }
        if (str_contains($ligne, '=')) {
            [$cle, $valeur] = explode('=', $ligne, 2);
            putenv(trim($cle) . '=' . trim($valeur));
        }
    }
}

// Session PHP (utilisée pour l'authentification, voir app/Core/Auth.php)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Affichage des erreurs en développement — À DÉSACTIVER en production
error_reporting(E_ALL);
ini_set('display_errors', '1');
