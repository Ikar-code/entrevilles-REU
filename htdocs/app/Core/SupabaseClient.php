<?php
/**
 * Client HTTP pour l'API REST de Supabase (PostgREST).
 * Remplace l'ancienne connexion PDO (pgsql), impossible sur InfinityFree
 * car l'extension pdo_pgsql n'y est pas disponible.
 *
 * Utilise l'API REST auto-générée par Supabase pour chaque table :
 * GET/POST/PATCH/DELETE sur https://<projet>.supabase.co/rest/v1/<table>
 *
 * Documentation Supabase REST : https://supabase.com/docs/guides/api
 */
class SupabaseClient
{
    private static ?string $url = null;
    private static ?string $key = null;

    private static function init(): void
    {
        if (self::$url === null) {
            self::$url = rtrim((string) getenv('SUPABASE_URL'), '/');
            self::$key = (string) getenv('SUPABASE_KEY');

            if (self::$url === '' || self::$key === '') {
                die('Erreur de connexion à la base de données : variables SUPABASE_URL / SUPABASE_KEY manquantes.');
            }
        }
    }

    /**
     * Requête HTTP générique vers PostgREST.
     * $method : GET, POST, PATCH, DELETE
     * $path   : ex. "ville?select=*&order=nom" ou "rpc/nom_fonction"
     * $body   : tableau à envoyer en JSON (POST/PATCH), null sinon
     * $prefer : en-tête "Prefer" (ex. "return=representation")
     */
    private static function requete(string $method, string $path, ?array $body = null, string $prefer = ''): array
    {
        self::init();

        $ch = curl_init(self::$url . '/rest/v1/' . $path);

        $entetes = [
            'apikey: ' . self::$key,
            'Authorization: Bearer ' . self::$key,
            'Content-Type: application/json',
        ];
        if ($prefer !== '') {
            $entetes[] = 'Prefer: ' . $prefer;
        }

        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_HTTPHEADER     => $entetes,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 15,
        ]);

        if ($body !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }

        $reponse = curl_exec($ch);

        if ($reponse === false) {
            $erreur = curl_error($ch);
            curl_close($ch);
            die('Erreur de connexion à la base de données : ' . $erreur);
        }

        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($code >= 400) {
            die('Erreur base de données (HTTP ' . $code . ') : ' . $reponse);
        }

        if ($reponse === '' || $reponse === null) {
            return [];
        }

        $donnees = json_decode($reponse, true);
        return is_array($donnees) ? $donnees : [];
    }

    /**
     * SELECT.
     * $table   : nom de la table (ou vue)
     * $select  : colonnes/embeds PostgREST, ex. "*,ville:ville_id(nom)"
     * $filtres : ex. ['id' => 'eq.5', 'statut' => 'eq.a_venir']
     * $ordre   : ex. "nom.asc" ou "date_heure.desc"
     */
    public static function select(string $table, string $select = '*', array $filtres = [], string $ordre = ''): array
    {
        $params = ['select' => $select];
        foreach ($filtres as $colonne => $valeur) {
            $params[$colonne] = $valeur;
        }
        if ($ordre !== '') {
            $params['order'] = $ordre;
        }

        return self::requete('GET', $table . '?' . http_build_query($params));
    }

    /** INSERT — renvoie la ligne insérée (grâce à Prefer: return=representation) */
    public static function insert(string $table, array $donnees): array
    {
        $resultat = self::requete('POST', $table, $donnees, 'return=representation');
        return $resultat[0] ?? [];
    }

    /** UPDATE — $filtres ex. ['id' => 'eq.5'] */
    public static function update(string $table, array $filtres, array $donnees): void
    {
        $params = [];
        foreach ($filtres as $colonne => $valeur) {
            $params[$colonne] = $valeur;
        }
        self::requete('PATCH', $table . '?' . http_build_query($params), $donnees);
    }

    /** DELETE — $filtres ex. ['id' => 'eq.5'] */
    public static function delete(string $table, array $filtres): void
    {
        $params = [];
        foreach ($filtres as $colonne => $valeur) {
            $params[$colonne] = $valeur;
        }
        self::requete('DELETE', $table . '?' . http_build_query($params));
    }

    /** Appelle une fonction Postgres (stored procedure) exposée via /rpc/ */
    public static function rpc(string $fonction, array $params = []): array
    {
        return self::requete('POST', 'rpc/' . $fonction, $params);
    }
}
