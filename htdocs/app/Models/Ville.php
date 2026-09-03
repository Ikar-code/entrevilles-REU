<?php
class Ville
{
    public static function toutes(): array
    {
        return SupabaseClient::select('ville', '*', [], 'nom.asc');
    }

    public static function trouver(int $id): ?array
    {
        $resultats = SupabaseClient::select('ville', '*', ['id' => 'eq.' . $id]);
        return $resultats[0] ?? null;
    }

    public static function creer(string $nom): int
    {
        $ligne = SupabaseClient::insert('ville', ['nom' => $nom]);
        return (int) $ligne['id'];
    }

    public static function modifier(int $id, string $nom): void
    {
        SupabaseClient::update('ville', ['id' => 'eq.' . $id], ['nom' => $nom]);
    }

    public static function supprimer(int $id): void
    {
        SupabaseClient::delete('ville', ['id' => 'eq.' . $id]);
    }
}
