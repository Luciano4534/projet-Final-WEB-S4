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
        <h2><?= esc($title) ?></h2>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('errors') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="alert alert-info">
                            Solde actuel : <strong><?= number_format($client->solde, 0, ',', '.') ?> AR</strong>
                            <?php if (($client->credit_retrait ?? 0) > 0): ?>
                                <br><small class="text-success">Crédit retrait disponible : <strong><?= number_format($client->credit_retrait, 0, ',', '.') ?> AR</strong> — vos frais de retrait seront réduits ou annulés.</small>
                            <?php endif; ?>
                        </div>

                        <form action="/client/retrait" method="POST">
                            <?= csrf_field() ?>

                            <div class="mb-3">
                                <label for="montant" class="form-label">Montant à retirer (AR)</label>
                                <input type="number" class="form-control" id="montant" name="montant" required
                                       min="1" step="1" placeholder="Ex: 5000"
                                       value="<?= old('montant') ?>">
                                <div class="form-text">Des frais seront appliqués selon le barème en vigueur.</div>
                            </div>

                            <button type="submit" class="btn btn-warning w-100">Effectuer le retrait</button>
                            <a href="/client/solde" class="btn btn-secondary w-100 mt-2">Annuler</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
