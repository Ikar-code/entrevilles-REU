<?php
/**
 * Contrôleur de base : fournit une méthode afficher() qui injecte des
 * variables dans une vue, elle-même enveloppée par le layout commun.
 */
class Controller
{
    protected function afficher(string $vue, array $donnees = []): void
    {
        extract($donnees); // transforme ['titre' => 'X'] en variable $titre

        $cheminVue = __DIR__ . '/../Views/' . $vue . '.php';

        // Le layout inclut la navbar/footer et charge le contenu de la vue
        require __DIR__ . '/../Views/partials/layout.php';
    }
}
