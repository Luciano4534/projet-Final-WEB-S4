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

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title text-muted">Bienvenue, <?= esc($client->prenom) ?> <?= esc($client->nom) ?></h5>
                        <p class="text-muted">N° <?= esc($client->telephone) ?></p>

                        <div class="my-4">
                            <h6 class="text-muted">Solde actuel</h6>
                            <h1 class="display-4 text-success"><?= number_format($client->solde, 0, ',', '.') ?> AR</h1>
                            <?php if (($client->credit_retrait ?? 0) > 0): ?>
                                <div class="mt-2">
                                    <span class="badge bg-success">Crédit retrait : <?= number_format($client->credit_retrait, 0, ',', '.') ?> AR</span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <hr>

                        <div class="row mt-3">
                            <div class="col">
                                <a href="/client/depot" class="btn btn-primary w-100">Dépôt</a>
                            </div>
                            <div class="col">
                                <a href="/client/retrait" class="btn btn-warning w-100">Retrait</a>
                            </div>
                            <div class="col">
                                <a href="/client/transfert" class="btn btn-info w-100">Transfert</a>
                            </div>
                            <div class="col">
                                <a href="/client/transfert-multiple" class="btn btn-secondary w-100">Envoi multiple</a>
                            </div>
                        </div>

                        <div class="mt-3">
                            <a href="/client/historique" class="btn btn-outline-secondary w-100">Voir l'historique</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
