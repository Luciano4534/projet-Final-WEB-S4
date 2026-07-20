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
<<<<<<< HEAD
                <a href="/dashboard" class="btn btn-outline-light btn-sm me-2">Dashboard</a>
=======
                <a href="/" class="btn btn-outline-light btn-sm me-2">Accueil</a>
>>>>>>> student/luciano
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

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Liste des Préfixes</h2>
            <a href="/prefixes/create" class="btn btn-success">+ Ajouter un préfixe</a>
        </div>

        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Code</th>
                    <th>Créé le</th>
                    <th>Modifié le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($prefixes)): ?>
                    <tr><td colspan="5" class="text-center">Aucun préfixe trouvé.</td></tr>
                <?php else: ?>
                    <?php foreach ($prefixes as $prefixe): ?>
                        <tr>
                            <td><?= esc($prefixe->id) ?></td>
                            <td><span class="badge bg-secondary"><?= esc($prefixe->code) ?></span></td>
                            <td><?= esc($prefixe->created_at) ?></td>
                            <td><?= esc($prefixe->updated_at) ?></td>
                            <td>
                                <a href="/prefixes/edit/<?= $prefixe->id ?>" class="btn btn-warning btn-sm">Modifier</a>
                                <form action="/prefixes/delete/<?= $prefixe->id ?>" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer ce préfixe ?')">
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
