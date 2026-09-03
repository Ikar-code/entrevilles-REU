<?php
/**
 * Table d'association equipe <-> utilisateur (le "roster").
 */
class MembreEquipe
{
    public static function parEquipe(int $equipeId): array
    {
        $lignes = SupabaseClient::select(
            'membre_equipe',
            '*,utilisateur:utilisateur_id(nom_compte,email)',
            ['equipe_id' => 'eq.' . $equipeId]
        );
        $lignes = array_map(function (array $ligne) {
            $ligne['nom_compte'] = $ligne['utilisateur']['nom_compte'] ?? null;
            $ligne['email']      = $ligne['utilisateur']['email'] ?? null;
            unset($ligne['utilisateur']);
            return $ligne;
        }, $lignes);

        // Le capitaine en premier, puis tri alphabétique par nom de compte
        // (PostgREST ne permet pas de trier sur une expression booléenne côté requête)
        usort($lignes, function (array $a, array $b) {
            $capitaineA = $a['role_interne'] === 'capitaine' ? 0 : 1;
            $capitaineB = $b['role_interne'] === 'capitaine' ? 0 : 1;
            return $capitaineA <=> $capitaineB ?: strcmp((string) $a['nom_compte'], (string) $b['nom_compte']);
        });

        return $lignes;
    }

    /** Équipe(s) dont fait partie un utilisateur donné — utilisé par la page /profil */
    public static function parUtilisateur(int $utilisateurId): array
    {
        $lignes = SupabaseClient::select(
            'membre_equipe',
            '*,equipe:equipe_id(nom,ville:ville_id(nom),sport:sport_id(nom))',
            ['utilisateur_id' => 'eq.' . $utilisateurId]
        );
        return array_map(function (array $ligne) {
            $ligne['equipe_nom'] = $ligne['equipe']['nom'] ?? null;
            $ligne['ville_nom']  = $ligne['equipe']['ville']['nom'] ?? null;
            $ligne['sport_nom']  = $ligne['equipe']['sport']['nom'] ?? null;
            $ligne['equipe_id']  = $ligne['equipe_id'] ?? null;
            unset($ligne['equipe']);
            return $ligne;
        }, $lignes);
    }

    public static function ajouter(int $equipeId, int $utilisateurId, string $roleInterne = 'joueur'): void
    {
        SupabaseClient::insert('membre_equipe', [
            'equipe_id' => $equipeId, 'utilisateur_id' => $utilisateurId, 'role_interne' => $roleInterne,
        ]);
    }

    public static function definirPenalite(int $equipeId, int $utilisateurId, ?string $penalite): void
    {
        SupabaseClient::update(
            'membre_equipe',
            ['equipe_id' => 'eq.' . $equipeId, 'utilisateur_id' => 'eq.' . $utilisateurId],
            ['penalite' => $penalite]
        );
    }

    public static function definirRecompense(int $equipeId, int $utilisateurId, ?string $recompense): void
    {
        SupabaseClient::update(
            'membre_equipe',
            ['equipe_id' => 'eq.' . $equipeId, 'utilisateur_id' => 'eq.' . $utilisateurId],
            ['recompense' => $recompense]
        );
    }

    public static function retirer(int $equipeId, int $utilisateurId): void
    {
        SupabaseClient::delete('membre_equipe', [
            'equipe_id' => 'eq.' . $equipeId, 'utilisateur_id' => 'eq.' . $utilisateurId,
        ]);
    }
}
