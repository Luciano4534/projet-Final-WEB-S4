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
                            Solde actuel : <strong><?= number_format($client->solde, 0, ',', '.') ?> F</strong>
                            <?php if (($client->credit_retrait ?? 0) > 0): ?>
                                <br><small class="text-success">Crédit retrait disponible : <strong><?= number_format($client->credit_retrait, 0, ',', '.') ?> F</strong></small>
                            <?php endif; ?>
                        </div>

                        <form action="/client/transfert" method="POST">
                            <?= csrf_field() ?>

                            <div class="mb-3">
                                <label for="telephone_dest" class="form-label">Numéro du destinataire</label>
                                <div class="input-group">
                                    <span class="input-group-text">+243</span>
                                    <input type="tel" class="form-control" id="telephone_dest" name="telephone_dest"
                                           required placeholder="Ex: 0331234567"
                                           value="<?= old('telephone_dest') ?>"
                                           pattern="[0-9]{10,15}" maxlength="15">
                                </div>
                                <div id="operateur-info" class="form-text text-info" style="display:none;"></div>
                            </div>

                            <div class="mb-3">
                                <label for="montant" class="form-label">Montant à transférer (F)</label>
                                <input type="number" class="form-control" id="montant" name="montant" required
                                       min="1" step="1" placeholder="Ex: 5000"
                                       value="<?= old('montant') ?>">
                            </div>

                            <div class="mb-3" id="option-retrait-section" style="display:none;">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="inclure_frais_retrait" name="inclure_frais_retrait" value="1">
                                    <label class="form-check-label" for="inclure_frais_retrait">
                                        Inclure les frais de retrait pour le destinataire
                                    </label>
                                </div>
                                <div class="form-text text-muted" id="retrait-help"></div>
                            </div>

                            <div class="alert alert-warning" id="alert-interoperateur" style="display:none;">
                                <strong>Transfert inter-opérateur</strong><br>
                                <small>Des frais supplémentaires de <span id="pct-display">0</span>% seront appliqués.</small>
                            </div>

                            <button type="submit" class="btn btn-info w-100">Effectuer le transfert</button>
                            <a href="/client/solde" class="btn btn-secondary w-100 mt-2">Annuler</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const ownOperateur = <?= json_encode(
            function_exists('session') ? (session()->get('user.telephone') ?? '') : ''
        ) ?>;

        const prefixes = <?= json_encode(
            function_exists('App\Models\PrefixesModel')
                ? (new App\Models\PrefixesModel())->orderBy('code', 'ASC')->findAll()
                : []
        ) ?>;

        function detectOperateur(telephone) {
            if (telephone.length < 3) return null;
            const prefix = telephone.substring(0, 3);
            for (const p of prefixes) {
                if (p.code === prefix) {
                    return { operateur: p.operateur, commission_pct: p.commission_pct };
                }
            }
            return null;
        }

        function getOwnOperateur() {
            const ownTel = <?= json_encode($client->telephone ?? '') ?>;
            return detectOperateur(ownTel);
        }

        document.getElementById('telephone_dest').addEventListener('input', function() {
            const tel = this.value.trim();
            const infoDiv = document.getElementById('operateur-info');
            const retraitSection = document.getElementById('option-retrait-section');
            const interOpAlert = document.getElementById('alert-interoperateur');
            const checkbox = document.getElementById('inclure_frais_retrait');
            const retraitHelp = document.getElementById('retrait-help');

            if (tel.length < 3) {
                infoDiv.style.display = 'none';
                retraitSection.style.display = 'none';
                interOpAlert.style.display = 'none';
                return;
            }

            const dest = detectOperateur(tel);
            const own = getOwnOperateur();

            if (dest) {
                infoDiv.style.display = 'block';
                infoDiv.textContent = 'Opérateur détecté : ' + dest.operateur;

                if (own && dest.operateur === own.operateur) {
                    retraitSection.style.display = 'block';
                    interOpAlert.style.display = 'none';
                    retraitHelp.textContent = 'Même opérateur - le destinataire pourra retirer gratuitement si vous cochez cette option.';
                    checkbox.disabled = false;
                } else {
                    retraitSection.style.display = 'none';
                    checkbox.checked = false;
                    checkbox.disabled = true;
                    retraitHelp.textContent = '';

                    if (dest.commission_pct > 0) {
                        interOpAlert.style.display = 'block';
                        document.getElementById('pct-display').textContent = dest.commission_pct;
                    } else {
                        interOpAlert.style.display = 'none';
                    }
                }
            } else {
                infoDiv.style.display = 'none';
                retraitSection.style.display = 'none';
                interOpAlert.style.display = 'none';
            }
        });
    </script>
</body>
</html>
