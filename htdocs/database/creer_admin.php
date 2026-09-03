<?php
/**
 * Script à lancer UNE FOIS pour créer le premier compte admin.
 *
 * IMPORTANT : exécute d'abord database/schema.sql PUIS
 * database/migration_mot_de_passe.sql dans Supabase avant de lancer ce
 * script (la colonne mot_de_passe n'existe pas dans le schéma d'origine).
 *
 * Utilisation : php database/creer_admin.php
 * Supprime ce fichier une fois utilisé — ne le laisse jamais accessible
 * publiquement en production.
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Core/Database.php';

$nomCompte = 'Administrateur';
$email = 'admin@entrevilles-reu.re';
$motDePasseClair = 'changez-moi123'; // change-le juste après ta première connexion

$hash = password_hash($motDePasseClair, PASSWORD_DEFAULT);

$pdo = Database::connexion();
$stmt = $pdo->prepare(
    "INSERT INTO utilisateur (email, nom_compte, role, mot_de_passe)
     VALUES (:email, :nom_compte, 'admin', :hash)
     ON CONFLICT (email) DO NOTHING"
);
$stmt->execute(['email' => $email, 'nom_compte' => $nomCompte, 'hash' => $hash]);

echo "Compte admin créé (ou déjà existant) : $email / $motDePasseClair\n";
echo "Pense à supprimer ce fichier une fois utilisé.\n";
