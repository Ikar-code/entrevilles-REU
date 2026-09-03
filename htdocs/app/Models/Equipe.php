<?php
class Equipe
{
    /** Aplati les objets imbriqués ville:{nom} et sport:{nom} en ville_nom / sport_nom */
    private static function aplatir(array $ligne): array
    {
        $ligne['ville_nom'] = $ligne['ville']['nom'] ?? null;
        $ligne['sport_nom'] = $ligne['sport']['nom'] ?? null;
        unset($ligne['ville'], $ligne['sport']);
        return $ligne;
    }

    public static function toutes(): array
    {
        $lignes = SupabaseClient::select(
            'equipe',
            '*,ville:ville_id(nom),sport:sport_id(nom)',
            [],
            'nom.asc'
        );
        return array_map([self::class, 'aplatir'], $lignes);
    }

    public static function trouver(int $id): ?array
    {
        $resultats = SupabaseClient::select(
            'equipe',
            '*,ville:ville_id(nom),sport:sport_id(nom)',
            ['id' => 'eq.' . $id]
        );
        return isset($resultats[0]) ? self::aplatir($resultats[0]) : null;
    }

    public static function parVille(int $villeId): array
    {
        return SupabaseClient::select('equipe', '*', ['ville_id' => 'eq.' . $villeId], 'nom.asc');
    }

    public static function creer(string $nom, int $villeId, int $sportId): int
    {
        $ligne = SupabaseClient::insert('equipe', [
            'nom' => $nom, 'ville_id' => $villeId, 'sport_id' => $sportId,
        ]);
        return (int) $ligne['id'];
    }

    public static function supprimer(int $id): void
    {
        SupabaseClient::delete('equipe', ['id' => 'eq.' . $id]);
    }
}
