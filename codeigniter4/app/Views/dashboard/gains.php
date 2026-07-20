<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - Mobile Money</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="/dashboard">Mobile Money</a>
            <div class="d-flex">
                <a href="/dashboard" class="btn btn-outline-light btn-sm me-2">Dashboard</a>
                <a href="/prefixes" class="btn btn-outline-light btn-sm me-2">Préfixes</a>
                <a href="/baremes" class="btn btn-outline-light btn-sm me-2">Barèmes</a>
                <a href="/types-operations" class="btn btn-outline-light btn-sm me-2">Types d'opérations</a>
                <a href="/logout" class="btn btn-outline-light btn-sm">Déconnexion</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2 class="mb-4"><?= esc($title) ?></h2>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white bg-success shadow">
                    <div class="card-body text-center">
                        <h6 class="card-title">Total frais perçus</h6>
                        <h2 class="display-6"><?= number_format($totalFrais, 0, ',', '.') ?> F</h2>
                    </div>
                </div>
            </div>
        </div>

        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Type d'opération</th>
                    <th>Nb transactions</th>
                    <th>Total frais</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($fraisByType)): ?>
                    <tr><td colspan="3" class="text-center">Aucune transaction enregistrée.</td></tr>
                <?php else: ?>
                    <?php foreach ($fraisByType as $ft): ?>
                        <tr>
                            <td><span class="badge bg-info"><?= esc($ft->type_libelle) ?></span></td>
                            <td><?= $ft->nb_transactions ?></td>
                            <td><strong><?= number_format($ft->total_frais, 0, ',', '.') ?> F</strong></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="/dashboard" class="btn btn-secondary">Retour au dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
