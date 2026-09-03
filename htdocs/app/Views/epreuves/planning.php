<h1>Planning des épreuves</h1>

<table>
    <thead>
        <tr>
            <th>Sport</th>
            <th>Ville</th>
            <th>Date</th>
            <th>Statut</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($epreuves as $epreuve): ?>
            <tr>
                <td><a href="/epreuves/<?= $epreuve['id'] ?>"><?= htmlspecialchars($epreuve['sport_nom']) ?></a></td>
                <td><?= htmlspecialchars($epreuve['ville_nom']) ?></td>
                <td><?= $epreuve['date_heure'] ? (new DateTime($epreuve['date_heure']))->format('d/m/Y H:i') : '—' ?></td>
                <td><span class="badge badge-<?= htmlspecialchars($epreuve['statut'] ?? '') ?>"><?= htmlspecialchars($epreuve['statut'] ?? '—') ?></span></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
