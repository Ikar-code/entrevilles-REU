-- À exécuter une seule fois dans Supabase → SQL Editor.
-- Remplace l'ancienne requête PHP avec GROUP BY / SUM, impossible à faire
-- via une simple requête REST PostgREST (qui n'agrège pas à la volée).
-- La vue, elle, est exposée automatiquement comme une table par l'API REST.

CREATE OR REPLACE VIEW vue_classement_general AS
SELECT
    v.id  AS ville_id,
    v.nom AS ville_nom,
    COALESCE(SUM(p.score), 0) AS total_points
FROM ville v
LEFT JOIN equipe e        ON e.ville_id = v.id
LEFT JOIN participation p ON p.equipe_id = e.id AND p.score IS NOT NULL
GROUP BY v.id, v.nom;
