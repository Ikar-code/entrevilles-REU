<?php
/**
 * Gestion de l'authentification via les sessions PHP natives.
 * Pas de dépendance à Supabase Auth : tout est géré ici.
 */
class Auth
{
    public static function connecter(array $utilisateur): void
    {
        // On ne stocke JAMAIS le mot de passe (même hashé) en session
        $_SESSION['utilisateur'] = [
            'id'         => $utilisateur['id'],
            'nom_compte' => $utilisateur['nom_compte'],
            'email'      => $utilisateur['email'],
            'role'       => $utilisateur['role'],
            'ville_id'   => $utilisateur['ville_id'],
        ];
    }

    public static function deconnecter(): void
    {
        unset($_SESSION['utilisateur']);
        session_destroy();
    }

    public static function estConnecte(): bool
    {
        return isset($_SESSION['utilisateur']);
    }

    public static function estAdmin(): bool
    {
        return self::estConnecte() && $_SESSION['utilisateur']['role'] === 'admin';
    }

    public static function utilisateur(): ?array
    {
        return $_SESSION['utilisateur'] ?? null;
    }

    /** Bloque l'accès à la page si pas connecté */
    public static function exigerConnexion(): void
    {
        if (!self::estConnecte()) {
            header('Location: /login');
            exit;
        }
    }

    /** Bloque l'accès à la page si pas admin */
    public static function exigerAdmin(): void
    {
        self::exigerConnexion();
        if (!self::estAdmin()) {
            http_response_code(403);
            die('Accès refusé : réservé aux administrateurs.');
        }
    }
}
