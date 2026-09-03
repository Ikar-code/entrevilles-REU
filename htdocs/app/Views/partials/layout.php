<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titre) ? htmlspecialchars($titre) . ' — ' : '' ?>Entrevilles-Reu</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <?php require __DIR__ . '/navbar.php'; ?>

    <main class="conteneur">
        <?php require $cheminVue; ?>
    </main>

    <?php require __DIR__ . '/footer.php'; ?>
</body>
</html>
