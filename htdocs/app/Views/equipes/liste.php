<h1>Équipes</h1>

<div class="grille-cartes">
    <?php foreach ($equipes as $equipe): ?>
        <div class="carte">
            <h3><?= htmlspecialchars($equipe['nom']) ?></h3>
            <p>Ville : <?= htmlspecialchars($equipe['ville_nom']) ?></p>
            <p>Sport : <?= htmlspecialchars($equipe['sport_nom']) ?></p>
            <a href="/equipes/<?= $equipe['id'] ?>" class="bouton">Voir l'équipe</a>
        </div>
    <?php endforeach; ?>
</div>
