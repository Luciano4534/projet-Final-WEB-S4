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

        <form action="/baremes/store" method="POST" class="mt-3" style="max-width: 500px;">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="type_operation_id" class="form-label">Type d'opération</label>
                <select class="form-select" id="type_operation_id" name="type_operation_id" required>
                    <option value="">-- Sélectionner --</option>
                    <?php foreach ($types as $type): ?>
                        <option value="<?= $type->id ?>" <?= old('type_operation_id') == $type->id ? 'selected' : '' ?>>
                            <?= esc($type->libelle) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="montant_min" class="form-label">Montant minimum (F)</label>
                <input type="number" class="form-control" id="montant_min" name="montant_min" required
                       min="0" step="1" placeholder="Ex: 0"
                       value="<?= old('montant_min') ?>">
            </div>

            <div class="mb-3">
                <label for="montant_max" class="form-label">Montant maximum (F)</label>
                <input type="number" class="form-control" id="montant_max" name="montant_max" required
                       min="1" step="1" placeholder="Ex: 10000"
                       value="<?= old('montant_max') ?>">
            </div>

            <div class="mb-3">
                <label for="frais" class="form-label">Frais (F)</label>
                <input type="number" class="form-control" id="frais" name="frais" required
                       min="0" step="1" placeholder="Ex: 100"
                       value="<?= old('frais') ?>">
            </div>

            <button type="submit" class="btn btn-success">Ajouter</button>
            <a href="/baremes" class="btn btn-secondary">Annuler</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
