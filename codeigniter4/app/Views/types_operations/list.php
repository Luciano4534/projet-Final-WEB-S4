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
            <a class="navbar-brand" href="/">Mobile Money</a>
            <div class="d-flex">
                <a href="/" class="btn btn-outline-light btn-sm me-2">Accueil</a>
                <a href="/prefixes" class="btn btn-outline-light btn-sm me-2">Préfixes</a>
                <a href="/types-operations" class="btn btn-outline-light btn-sm me-2">Types d'opérations</a>
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

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Liste des Types d'Opérations</h2>
            <a href="/types-operations/create" class="btn btn-success">+ Ajouter un type</a>
        </div>

        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Libellé</th>
                    <th>Description</th>
                    <th>Créé le</th>
                    <th>Modifié le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($types)): ?>
                    <tr><td colspan="6" class="text-center">Aucun type trouvé.</td></tr>
                <?php else: ?>
                    <?php foreach ($types as $type): ?>
                        <tr>
                            <td><?= esc($type->id) ?></td>
                            <td><span class="badge bg-info"><?= esc($type->libelle) ?></span></td>
                            <td><?= esc($type->description) ?></td>
                            <td><?= esc($type->created_at) ?></td>
                            <td><?= esc($type->updated_at) ?></td>
                            <td>
                                <a href="/types-operations/edit/<?= $type->id ?>" class="btn btn-warning btn-sm">Modifier</a>
                                <form action="/types-operations/delete/<?= $type->id ?>" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer ce type ?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
