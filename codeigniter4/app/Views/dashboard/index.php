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
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <h2 class="mb-4"><?= esc($title) ?></h2>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary shadow">
                    <div class="card-body text-center">
                        <h6 class="card-title">Clients</h6>
                        <h2 class="display-6"><?= $nbClients ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info shadow">
                    <div class="card-body text-center">
                        <h6 class="card-title">Transactions</h6>
                        <h2 class="display-6"><?= $nbTransactions ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success shadow">
                    <div class="card-body text-center">
                        <h6 class="card-title">Montant total</h6>
                        <h2 class="display-6"><?= number_format($totalMontant, 0, ',', '.') ?> AR</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning shadow">
                    <div class="card-body text-center">
                        <h6 class="card-title">Frais perçus</h6>
                        <h2 class="display-6"><?= number_format($totalFrais, 0, ',', '.') ?> AR</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title">Frais par type d'opération</h5>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Nb transactions</th>
                                    <th>Total frais</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($fraisByType)): ?>
                                    <tr><td colspan="3" class="text-center">Aucune transaction.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($fraisByType as $ft): ?>
                                        <tr>
                                            <td><span class="badge bg-info"><?= esc($ft->type_libelle) ?></span></td>
                                            <td><?= $ft->nb_transactions ?></td>
                                            <td><strong><?= number_format($ft->total_frais, 0, ',', '.') ?> AR</strong></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title">Accès rapides</h5>
                        <div class="d-grid gap-2">
                            <a href="/dashboard/clients" class="btn btn-outline-primary">Situation des comptes clients</a>
                            <a href="/dashboard/gains" class="btn btn-outline-success">Gains de l'opérateur</a>
                            <a href="/prefixes" class="btn btn-outline-secondary">Gestion des préfixes</a>
                            <a href="/baremes" class="btn btn-outline-secondary">Gestion des barèmes</a>
                            <a href="/types-operations" class="btn btn-outline-secondary">Types d'opérations</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
