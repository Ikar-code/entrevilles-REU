<?php
/**
 * Déclaration de toutes les routes de l'application.
 * Format : Router::get('/chemin/{param}', Controleur::class, 'methode');
 */

// Accueil
Router::get('/', AccueilController::class, 'index');

// Authentification
Router::get('/login', AuthController::class, 'afficherLogin');
Router::post('/login', AuthController::class, 'traiterLogin');
Router::get('/inscription', AuthController::class, 'afficherInscription');
Router::post('/inscription', AuthController::class, 'traiterInscription');
Router::get('/deconnexion', AuthController::class, 'deconnexion');

// Profil
Router::get('/profil', ProfilController::class, 'index');

// Épreuves
Router::get('/planning', EpreuveController::class, 'planning');
Router::get('/epreuves/{id}', EpreuveController::class, 'detail');

// Équipes
Router::get('/equipes', EquipeController::class, 'liste');
Router::get('/equipes/{id}', EquipeController::class, 'detail');

// Classement
Router::get('/classement', ClassementController::class, 'index');

// Administration
Router::get('/admin', AdminController::class, 'tableauDeBord');
Router::get('/admin/villes/nouvelle', AdminController::class, 'nouvelleVille');
Router::post('/admin/villes', AdminController::class, 'creerVille');
Router::post('/admin/villes/{id}/supprimer', AdminController::class, 'supprimerVille');
