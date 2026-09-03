<?php
class EquipeController extends Controller
{
    public function liste(): void
    {
        $equipes = Equipe::toutes();

        $this->afficher('equipes/liste', [
            'titre'   => 'Équipes',
            'equipes' => $equipes,
        ]);
    }

    public function detail(string $id): void
    {
        $equipe = Equipe::trouver((int) $id);

        if ($equipe === null) {
            http_response_code(404);
            require __DIR__ . '/../Views/erreur_404.php';
            return;
        }

        $membres = MembreEquipe::parEquipe((int) $id);

        $this->afficher('equipes/detail', [
            'titre'   => $equipe['nom'],
            'equipe'  => $equipe,
            'membres' => $membres,
        ]);
    }
}
