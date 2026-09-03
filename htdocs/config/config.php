<?php
/**
 * Charge la config (SUPABASE_URL / SUPABASE_KEY) puis démarre la session.
 * Deux sources possibles, dans cet ordre :
 *   1. config/env.local.php  → un fichier PHP (pas un .env), car InfinityFree
 *      bloque souvent l'upload/l'accès aux fichiers commençant par un point.
 *      C'est la méthode à utiliser sur l'hébergement en ligne.
 *   2. .env (à la racine)    → utile seulement si tu testes en local avec
 *      un hébergeur qui accepte les dotfiles.
 * Aucune dépendance externe (pas de composer).
 */

$envLocalPhp = __DIR__ . '/env.local.php';
$envPath = __DIR__ . '/../.env';

if (file_exists($envLocalPhp)) {
    require $envLocalPhp;
} elseif (file_exists($envPath)) {
    $lignes = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lignes as $ligne) {
        if (str_starts_with(trim($ligne), '#')) {
            continue;
        }
        if (str_contains($ligne, '=')) {
            [$cle, $valeur] = explode('=', $ligne, 2);
            putenv(trim($cle) . '=' . trim($valeur));
        }
    }
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', '1');
