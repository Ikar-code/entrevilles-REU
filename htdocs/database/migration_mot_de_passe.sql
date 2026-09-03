-- ============================================================
--  Migration complémentaire — à exécuter APRÈS BDDEntreVilles_supabase.sql
--  Ajoute ce qui manque pour permettre une connexion sécurisée en PHP.
--  Ne modifie pas le fichier de la BDD d'origine : à faire valider par
--  la personne qui gère la BDD avant de l'exécuter.
-- ============================================================

ALTER TABLE utilisateur ADD COLUMN IF NOT EXISTS mot_de_passe VARCHAR(255);

-- (optionnel mais conseillé) évite les doublons de compte par email
ALTER TABLE utilisateur ADD CONSTRAINT utilisateur_email_unique UNIQUE (email);
