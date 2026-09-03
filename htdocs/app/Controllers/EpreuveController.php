<?php
class EpreuveController extends Controller
{
    public function planning(): void
    {
        $epreuves = Epreuve::toutes();

        $this->afficher('epreuves/planning', [
            'titre'    => 'Planning',
            'epreuves' => $epreuves,
        ]);
    }

    public function detail(string $id): void
    {
        $epreuve = Epreuve::trouver((int) $id);

        if ($epreuve === null) {
            http_response_code(404);
            require __DIR__ . '/../Views/erreur_404.php';
            return;
        }

        $participations = Participation::parEpreuve((int) $id);

        $this->afficher('epreuves/detail', [
            'titre'          => $epreuve['sport_nom'] . ' — ' . $epreuve['ville_nom'],
            'epreuve'        => $epreuve,
            'participations' => $participations,
        ]);
    }
}
