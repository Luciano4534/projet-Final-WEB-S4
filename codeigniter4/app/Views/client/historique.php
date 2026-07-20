<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - Mobile Money</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-success mb-4">
        <div class="container">
            <a class="navbar-brand" href="/client/solde">Mobile Money</a>
            <div class="d-flex">
                <a href="/client/solde" class="btn btn-outline-light btn-sm me-2">Solde</a>
                <a href="/client/depot" class="btn btn-outline-light btn-sm me-2">Dépôt</a>
                <a href="/client/retrait" class="btn btn-outline-light btn-sm me-2">Retrait</a>
                <a href="/client/transfert" class="btn btn-outline-light btn-sm me-2">Transfert</a>
                <a href="/client/transfert-multiple" class="btn btn-outline-light btn-sm me-2">Envoi multiple</a>
                <a href="/client/historique" class="btn btn-outline-light btn-sm me-2">Historique</a>
                <a href="/logout" class="btn btn-outline-light btn-sm">Déconnexion</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <h2><?= esc($title) ?></h2>

        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Montant</th>
                    <th>Frais</th>
                    <th>Commission</th>
                    <th>Destinataire</th>
                    <th>Opérateur dest.</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($transactions)): ?>
                    <tr><td colspan="7" class="text-center">Aucune transaction trouvée.</td></tr>
                <?php else: ?>
                    <?php foreach ($transactions as $t): ?>
                        <tr>
                            <td><?= esc($t->created_at) ?></td>
                            <td><span class="badge bg-info"><?= esc($t->type_libelle) ?></span></td>
                            <td><?= number_format($t->montant, 0, ',', '.') ?> F</td>
                            <td><?= number_format($t->frais, 0, ',', '.') ?> F</td>
                            <td>
                                <?php if (($t->frais_commission ?? 0) > 0): ?>
                                    <span class="text-warning"><?= number_format($t->frais_commission, 0, ',', '.') ?> F</span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><?= $t->telephone_dest ? esc($t->telephone_dest) : '-' ?></td>
                            <td>
                                <?php if (!empty($t->operateur_dest)): ?>
                                    <span class="badge bg-secondary"><?= esc($t->operateur_dest) ?></span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <?= $pager->links('default', 'default_full') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
