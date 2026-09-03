<h1>Nouvelle ville</h1>

<?php if (!empty($erreur)): ?>
    <p class="erreur"><?= htmlspecialchars($erreur) ?></p>
<?php endif; ?>

<form method="POST" action="/admin/villes">
    <label for="nom">Nom de la ville</label>
    <input type="text" id="nom" name="nom" required>

    <button type="submit">Créer</button>
</form>
