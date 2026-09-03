<?php
class Epreuve
{
    private static function aplatir(array $ligne): array
    {
        $ligne['sport_nom'] = $ligne['sport']['nom'] ?? null;
        $ligne['ville_nom'] = $ligne['ville']['nom'] ?? null;
        unset($ligne['sport'], $ligne['ville']);
        return $ligne;
    }

    public static function toutes(): array
    {
        $lignes = SupabaseClient::select(
            'epreuve',
            '*,sport:sport_id(nom),ville:ville_id(nom)',
            [],
            'date_heure.desc'
        );
        return array_map([self::class, 'aplatir'], $lignes);
    }

    public static function trouver(int $id): ?array
    {
        $resultats = SupabaseClient::select(
            'epreuve',
            '*,sport:sport_id(nom),ville:ville_id(nom)',
            ['id' => 'eq.' . $id]
        );
        return isset($resultats[0]) ? self::aplatir($resultats[0]) : null;
    }

    public static function aVenir(): array
    {
        $lignes = SupabaseClient::select(
            'epreuve',
            '*,sport:sport_id(nom),ville:ville_id(nom)',
            ['statut' => 'eq.a_venir'],
            'date_heure.asc'
        );
        return array_map([self::class, 'aplatir'], $lignes);
    }

    public static function creer(int $sportId, int $villeId, ?string $dateHeure, string $statut = 'a_venir'): int
    {
        $ligne = SupabaseClient::insert('epreuve', [
            'sport_id' => $sportId, 'ville_id' => $villeId,
            'date_heure' => $dateHeure, 'statut' => $statut,
        ]);
        return (int) $ligne['id'];
    }

    public static function changerStatut(int $id, string $statut): void
    {
        SupabaseClient::update('epreuve', ['id' => 'eq.' . $id], ['statut' => $statut]);
    }

    public static function supprimer(int $id): void
    {
        SupabaseClient::delete('epreuve', ['id' => 'eq.' . $id]);
    }
}
