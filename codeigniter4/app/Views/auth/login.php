<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - Mobile Money</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .login-card {
            max-width: 420px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card login-card shadow">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <h2 class="text-primary">Mobile Money</h2>
                    <p class="text-muted">Connectez-vous avec votre numéro de téléphone</p>
                </div>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <form action="/login" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="telephone" class="form-label">Numéro de téléphone</label>
                        <div class="input-group">
                            <span class="input-group-text">+243</span>
                            <input type="tel" class="form-control" id="telephone" name="telephone"
                                   required placeholder="Ex: 0331234567"
                                   value="<?= old('telephone') ?>"
                                   pattern="[0-9]{10,15}" maxlength="15">
                        </div>
                        <div class="form-text">Le préfixe doit être valide (033, 037, 050...)</div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                </form>

                <div class="mt-3 text-center">
                    <small class="text-muted">
                        Pas encore de compte ? Entrez simplement votre numéro.<br>
                        Un compte sera créé automatiquement.
                    </small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
