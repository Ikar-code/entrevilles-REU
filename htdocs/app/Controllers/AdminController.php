<?php
class AdminController extends Controller
{
    public function __construct()
    {
        // Toutes les actions de ce contrôleur exigent d'être admin
        Auth::exigerAdmin();
    }

    public function tableauDeBord(): void
    {
        $this->afficher('admin/tableau_de_bord', [
            'titre'  => 'Administration',
            'villes' => Ville::toutes(),
            'sports' => Sport::tous(),
        ]);
    }

    // ------------------------------------------------------------------
    // CRUD Villes — sert de modèle à reproduire pour equipes/sports/epreuves
    // ------------------------------------------------------------------

    public function nouvelleVille(): void
    {
        $this->afficher('admin/villes/nouvelle', ['titre' => 'Nouvelle ville']);
    }

    public function creerVille(): void
    {
        $nom = trim($_POST['nom'] ?? '');

        if ($nom === '') {
            $this->afficher('admin/villes/nouvelle', [
                'titre'  => 'Nouvelle ville',
                'erreur' => 'Le nom est obligatoire.',
            ]);
            return;
        }

        Ville::creer($nom);
        header('Location: /admin');
        exit;
    }

    public function supprimerVille(string $id): void
    {
        Ville::supprimer((int) $id);
        header('Location: /admin');
        exit;
    }
}
