<?php
class Utilisateur
{
    public static function trouverParEmail(string $email): ?array
    {
        $resultats = SupabaseClient::select('utilisateur', '*', ['email' => 'eq.' . $email]);
        return $resultats[0] ?? null;
    }

    public static function trouver(int $id): ?array
    {
        $resultats = SupabaseClient::select('utilisateur', '*', ['id' => 'eq.' . $id]);
        return $resultats[0] ?? null;
    }

    public static function creer(string $nomCompte, string $email, string $motDePasseClair, string $role = 'joueur', ?int $villeId = null): int
    {
        $hash = password_hash($motDePasseClair, PASSWORD_DEFAULT);
        $ligne = SupabaseClient::insert('utilisateur', [
            'email' => $email,
            'nom_compte' => $nomCompte,
            'role' => $role,
            'ville_id' => $villeId,
            'mot_de_passe' => $hash,
        ]);
        return (int) $ligne['id'];
    }

    /** Vérifie email + mot de passe, renvoie l'utilisateur si ok, sinon null */
    public static function verifierIdentifiants(string $email, string $motDePasseClair): ?array
    {
        $utilisateur = self::trouverParEmail($email);
        if ($utilisateur && $utilisateur['mot_de_passe'] && password_verify($motDePasseClair, $utilisateur['mot_de_passe'])) {
            return $utilisateur;
        }
        return null;
    }
}
