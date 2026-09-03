<?php
/**
 * Table "participation" : fusionne l'inscription d'une équipe à une épreuve
 * ET son résultat (score, classement) une fois l'épreuve jouée.
 */
class Participation
{
    public static function parEpreuve(int $epreuveId): array
    {
        $lignes = SupabaseClient::select(
            'participation',
            '*,equipe:equipe_id(nom,ville:ville_id(nom))',
            ['epreuve_id' => 'eq.' . $epreuveId],
            'classement.asc.nullslast'
        );
        return array_map(function (array $ligne) {
            $ligne['equipe_nom'] = $ligne['equipe']['nom'] ?? null;
            $ligne['ville_nom']  = $ligne['equipe']['ville']['nom'] ?? null;
            unset($ligne['equipe']);
            return $ligne;
        }, $lignes);
    }

    public static function inscrire(int $epreuveId, int $equipeId, string $statut = 'en_attente'): void
    {
        SupabaseClient::insert('participation', [
            'epreuve_id' => $epreuveId, 'equipe_id' => $equipeId, 'statut' => $statut,
        ]);
    }

    public static function saisirResultat(int $epreuveId, int $equipeId, int $score, int $classement): void
    {
        SupabaseClient::update(
            'participation',
            ['epreuve_id' => 'eq.' . $epreuveId, 'equipe_id' => 'eq.' . $equipeId],
            ['score' => $score, 'classement' => $classement]
        );
    }

    public static function validerInscription(int $epreuveId, int $equipeId): void
    {
        SupabaseClient::update(
            'participation',
            ['epreuve_id' => 'eq.' . $epreuveId, 'equipe_id' => 'eq.' . $equipeId],
            ['statut' => 'validee']
        );
    }

    /**
     * Classement général : somme des scores de toutes les équipes de chaque ville.
     * Nécessite la vue SQL "vue_classement_general" côté Supabase (voir migration
     * database/vue_classement_general.sql) car PostgREST ne fait pas de GROUP BY
     * à la volée sur une simple requête REST.
     */
    public static function classementGeneral(): array
    {
        return SupabaseClient::select('vue_classement_general', '*', [], 'total_points.desc');
    }
}
