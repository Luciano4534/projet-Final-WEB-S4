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

        <form method="GET" action="/dashboard/clients" class="mb-4">
            <div class="input-group" style="max-width: 400px;">
                <input type="text" class="form-control" name="search" placeholder="Rechercher par nom, prénom, téléphone..." value="<?= esc($search ?? '') ?>">
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </div>
        </form>

        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Téléphone</th>
                    <th>Solde</th>
                    <th>Créé le</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($clients)): ?>
                    <tr><td colspan="6" class="text-center">Aucun client trouvé.</td></tr>
                <?php else: ?>
                    <?php foreach ($clients as $c): ?>
                        <tr>
                            <td><?= esc($c->id) ?></td>
                            <td><?= esc($c->nom) ?></td>
                            <td><?= esc($c->prenom) ?></td>
                            <td><span class="badge bg-secondary"><?= esc($c->telephone) ?></span></td>
                            <td><strong class="<?= $c->solde > 0 ? 'text-success' : 'text-danger' ?>"><?= number_format($c->solde, 0, ',', '.') ?> AR</strong></td>
                            <td><?= esc($c->created_at) ?></td>
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
