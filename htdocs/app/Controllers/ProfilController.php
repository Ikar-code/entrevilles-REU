<?php
class ProfilController extends Controller
{
    public function index(): void
    {
        Auth::exigerConnexion();

        $utilisateur = Utilisateur::trouver(Auth::utilisateur()['id']);
        $equipes = MembreEquipe::parUtilisateur(Auth::utilisateur()['id']);

        $this->afficher('profil/index', [
            'titre'       => 'Mon profil',
            'utilisateur' => $utilisateur,
            'equipes'     => $equipes,
        ]);
    }
}
