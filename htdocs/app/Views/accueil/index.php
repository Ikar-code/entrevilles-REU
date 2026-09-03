<div class="hero">
    <div class="hero-contenu">
        <h1>Le défi sportif des communes de La Réunion</h1>
        <p>Épreuves, équipes et classement inter-villes, en direct.</p>
    </div>
</div>

<h2>Prochaines épreuves</h2>

<?php if (empty($epreuvesAVenir)): ?>
    <p>Aucune épreuve à venir pour le moment.</p>
<?php else: ?>
    <div class="grille-cartes">
        <?php foreach ($epreuvesAVenir as $epreuve): ?>
            <div class="carte">
                <span class="badge badge-<?= htmlspecialchars($epreuve['statut']) ?>">
                    <?= htmlspecialchars($epreuve['statut']) ?>
                </span>
                <h3><?= htmlspecialchars($epreuve['sport_nom']) ?></h3>
                <p>À <?= htmlspecialchars($epreuve['ville_nom']) ?></p>
                <?php if ($epreuve['date_heure']): ?>
                    <p><?= (new DateTime($epreuve['date_heure']))->format('d/m/Y à H:i') ?></p>
                <?php endif; ?>
                <a href="/epreuves/<?= $epreuve['id'] ?>" class="bouton">Voir le détail</a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
