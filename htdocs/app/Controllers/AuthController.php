<?php
class AuthController extends Controller
{
    public function afficherLogin(): void
    {
        $this->afficher('auth/login', ['titre' => 'Connexion', 'erreur' => null]);
    }

    public function traiterLogin(): void
    {
        $email = trim($_POST['email'] ?? '');
        $motDePasse = $_POST['mot_de_passe'] ?? '';

        $utilisateur = Utilisateur::verifierIdentifiants($email, $motDePasse);

        if ($utilisateur === null) {
            $this->afficher('auth/login', [
                'titre'  => 'Connexion',
                'erreur' => 'Email ou mot de passe incorrect.',
            ]);
            return;
        }

        Auth::connecter($utilisateur);
        header('Location: /');
        exit;
    }

    public function afficherInscription(): void
    {
        $this->afficher('auth/inscription', [
            'titre'  => 'Inscription',
            'erreur' => null,
            'villes' => Ville::toutes(),
        ]);
    }

    public function traiterInscription(): void
    {
        $nomCompte = trim($_POST['nom_compte'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $motDePasse = $_POST['mot_de_passe'] ?? '';
        $motDePasseConfirmation = $_POST['mot_de_passe_confirmation'] ?? '';
        $villeId = $_POST['ville_id'] !== '' ? (int) $_POST['ville_id'] : null;

        $erreur = null;
        if ($nomCompte === '' || $email === '' || $motDePasse === '') {
            $erreur = 'Tous les champs marqués * sont obligatoires.';
        } elseif ($motDePasse !== $motDePasseConfirmation) {
            $erreur = 'Les deux mots de passe ne correspondent pas.';
        } elseif (strlen($motDePasse) < 8) {
            $erreur = 'Le mot de passe doit faire au moins 8 caractères.';
        } elseif (Utilisateur::trouverParEmail($email) !== null) {
            $erreur = 'Un compte existe déjà avec cet email.';
        }

        if ($erreur !== null) {
            $this->afficher('auth/inscription', [
                'titre'  => 'Inscription',
                'erreur' => $erreur,
                'villes' => Ville::toutes(),
            ]);
            return;
        }

        $id = Utilisateur::creer($nomCompte, $email, $motDePasse, 'joueur', $villeId);
        $utilisateur = Utilisateur::trouver($id);

        Auth::connecter($utilisateur);
        header('Location: /profil');
        exit;
    }

    public function deconnexion(): void
    {
        Auth::deconnecter();
        header('Location: /');
        exit;
    }
}
