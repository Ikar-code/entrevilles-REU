<h1>Administration</h1>

<h2>Villes</h2>
<a href="/admin/villes/nouvelle" class="bouton">+ Ajouter une ville</a>

<table>
    <thead>
        <tr><th>Nom</th><th></th></tr>
    </thead>
    <tbody>
        <?php foreach ($villes as $ville): ?>
            <tr>
                <td><?= htmlspecialchars($ville['nom']) ?></td>
                <td>
                    <form method="POST" action="/admin/villes/<?= $ville['id'] ?>/supprimer" onsubmit="return confirm('Supprimer cette ville ?');" style="display:inline;">
                        <button type="submit">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2>Sports</h2>
<ul>
    <?php foreach ($sports as $sport): ?>
        <li><?= htmlspecialchars($sport['nom']) ?></li>
    <?php endforeach; ?>
</ul>

<p style="margin-top:24px; color:#666; font-size:0.9rem;">
    Le CRUD complet (équipes, sports, épreuves, participations/résultats) suit
    le même modèle que celui des villes ci-dessus.
</p>
