<?php
/**
 * Autoloader maison (pas besoin de Composer pour un projet de cette taille).
 * Cherche la classe demandée dans app/Core, app/Models, app/Controllers.
 */
spl_autoload_register(function (string $classe) {
    $dossiers = ['Core', 'Models', 'Controllers'];

    foreach ($dossiers as $dossier) {
        $chemin = __DIR__ . '/../' . $dossier . '/' . $classe . '.php';
        if (file_exists($chemin)) {
            require $chemin;
            return;
        }
    }
});
