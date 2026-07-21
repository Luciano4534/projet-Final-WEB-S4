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
        <h2><?= esc($title) ?></h2>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('errors') ?></div>
        <?php endif; ?>

        <form action="/prefixes/update/<?= $prefixe->id ?>" method="POST" class="mt-3" style="max-width: 500px;">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="code" class="form-label">Code du préfixe</label>
                <input type="text" class="form-control" id="code" name="code" required
                       minlength="3" maxlength="10"
                       value="<?= esc($prefixe->code) ?>">
            </div>

            <div class="mb-3">
                <label for="operateur" class="form-label">Opérateur</label>
                <input type="text" class="form-control" id="operateur" name="operateur" required
                       maxlength="50"
                       value="<?= esc($prefixe->operateur) ?>"
                       list="operateurs-list">
                <datalist id="operateurs-list">
                    <?php if (!empty($operateurs)): ?>
                        <?php foreach ($operateurs as $op): ?>
                            <option value="<?= esc($op->operateur) ?>">
                        <?php endforeach; ?>
                    <?php endif; ?>
                </datalist>
            </div>

            <div class="mb-3">
                <label for="commission_pct" class="form-label">Commission inter-opérateur (%)</label>
                <input type="number" class="form-control" id="commission_pct" name="commission_pct"
                       min="0" max="100" step="0.1"
                       value="<?= esc($prefixe->commission_pct) ?>">
                <div class="form-text">Pourcentage appliqué aux transferts vers cet opérateur. Mettre 0 pour l'opérateur principal.</div>
            </div>

            <button type="submit" class="btn btn-warning">Modifier</button>
            <a href="/prefixes" class="btn btn-secondary">Annuler</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
