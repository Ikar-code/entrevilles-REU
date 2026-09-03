<h1>Classement général inter-villes</h1>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Ville</th>
            <th>Total points</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($classement as $i => $ligne): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($ligne['ville_nom']) ?></td>
                <td><strong><?= (int) $ligne['total_points'] ?></strong></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
