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
            <div class="col-md-4">
                <div class="card text-white bg-primary shadow">
                    <div class="card-body text-center">
                        <h6 class="card-title">Frais internes</h6>
                        <h3><?= number_format($totalFraisInternes, 0, ',', '.') ?> F</h3>
                        <small>Dépôt, Retrait, Transferts même opérateur</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning shadow">
                    <div class="card-body text-center">
                        <h6 class="card-title">Frais inter-opérateurs</h6>
                        <h3><?= number_format($totalFraisExternes, 0, ',', '.') ?> F</h3>
                        <small>Frais fixes + Commissions %</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Gains via frais internes</h5>
                        <p class="text-muted">Dépôts, retraits et transferts au sein de l'opérateur</p>
                        <table class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>Type d'opération</th>
                                    <th>Nb transactions</th>
                                    <th>Total frais</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($fraisInternes)): ?>
                                    <tr><td colspan="3" class="text-center">Aucune transaction interne.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($fraisInternes as $fi): ?>
                                        <tr>
                                            <td><span class="badge bg-primary"><?= esc($fi->type_libelle) ?></span></td>
                                            <td><?= $fi->nb_transactions ?></td>
                                            <td><strong><?= number_format($fi->total_frais, 0, ',', '.') ?> F</strong></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr class="table-active">
                                        <td colspan="2"><strong>Total interne</strong></td>
                                        <td><strong class="text-primary"><?= number_format($totalFraisInternes, 0, ',', '.') ?> F</strong></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title text-warning">Gains via frais inter-opérateurs</h5>
                        <p class="text-muted">Frais fixes et commissions pour les transferts vers d'autres opérateurs</p>
                        <table class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>Opérateur destinataire</th>
                                    <th>Nb transferts</th>
                                    <th>Frais fixes</th>
                                    <th>Commissions %</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($fraisExternes)): ?>
                                    <tr><td colspan="5" class="text-center">Aucun transfert inter-opérateur.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($fraisExternes as $fe): ?>
                                        <tr>
                                            <td><span class="badge bg-warning text-dark"><?= esc($fe->operateur_dest) ?></span></td>
                                            <td><?= $fe->nb_transactions ?></td>
                                            <td><?= number_format($fe->total_frais_fixe, 0, ',', '.') ?> F</td>
                                            <td><?= number_format($fe->total_frais_commission, 0, ',', '.') ?> F</td>
                                            <td><strong><?= number_format($fe->total_frais, 0, ',', '.') ?> F</strong></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr class="table-active">
                                        <td colspan="3"><strong>Total externe</strong></td>
                                        <td><strong><?= number_format($totalFraisCommission, 0, ',', '.') ?> F</strong></td>
                                        <td><strong class="text-warning"><?= number_format($totalFraisExternes, 0, ',', '.') ?> F</strong></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title text-danger">Situation des montants à envoyer à chaque opérateur</h5>
                        <p class="text-muted">Montants totaux transférés vers chaque opérateur autre — montant à verser</p>
                        <table class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>Opérateur</th>
                                    <th>Nb transferts</th>
                                    <th>Montant total transféré</th>
                                    <th>Frais fixes perçus</th>
                                    <th>Commissions % perçues</th>
                                    <th>Total à verser</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($montantsParOperateur)): ?>
                                    <tr><td colspan="6" class="text-center">Aucun transfert vers d'autres opérateurs.</td></tr>
                                <?php else: ?>
                                    <?php
                                    $grandTotalMontant = 0;
                                    $grandTotalAVerser = 0;
                                    ?>
                                    <?php foreach ($montantsParOperateur as $mpo): ?>
                                        <?php
                                        $grandTotalMontant += $mpo->total_montant;
                                        $grandTotalAVerser += $mpo->total_a_verser;
                                        ?>
                                        <tr>
                                            <td><span class="badge bg-warning text-dark"><?= esc($mpo->operateur_dest) ?></span></td>
                                            <td><?= $mpo->nb_transactions ?></td>
                                            <td><?= number_format($mpo->total_montant, 0, ',', '.') ?> F</td>
                                            <td><?= number_format($mpo->total_frais_fixe, 0, ',', '.') ?> F</td>
                                            <td><?= number_format($mpo->total_commission, 0, ',', '.') ?> F</td>
                                            <td><strong class="text-danger"><?= number_format($mpo->total_a_verser, 0, ',', '.') ?> F</strong></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr class="table-active">
                                        <td colspan="2"><strong>Total général</strong></td>
                                        <td><strong><?= number_format($grandTotalMontant, 0, ',', '.') ?> F</strong></td>
                                        <td colspan="2"></td>
                                        <td><strong class="text-danger"><?= number_format($grandTotalAVerser, 0, ',', '.') ?> F</strong></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <a href="/dashboard" class="btn btn-secondary mt-3">Retour au dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
