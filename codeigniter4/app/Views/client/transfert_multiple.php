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
        <p class="text-muted">Envoyez de l'argent à plusieurs destinataires du même opérateur en une seule opération.</p>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('errors') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="alert alert-info">
                            Solde actuel : <strong><?= number_format($client->solde, 0, ',', '.') ?> AR</strong>
                            <br><small>Opérateur : <strong><?= esc($ownOperateur) ?></strong> — Tous les destinataires doivent appartenir à cet opérateur.</small>
                        </div>

                        <form action="/client/transfert-multiple" method="POST" id="form-multiple">
                            <?= csrf_field() ?>

                            <div id="destinataires-container">
                                <div class="destinataire-row card mb-3" data-index="0">
                                    <div class="card-body">
                                        <div class="row g-2 align-items-end">
                                            <div class="col-md-5">
                                                <label class="form-label">Numéro destinataire</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">+243</span>
                                                    <input type="tel" class="form-control telephone-input" name="destinataires[0][telephone]"
                                                           required placeholder="0331234567" pattern="[0-9]{10,15}" maxlength="15">
                                                </div>
                                                <div class="form-text operateur-label text-info"></div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Montant (AR)</label>
                                                <input type="number" class="form-control montant-input" name="destinataires[0][montant]"
                                                       required min="1" step="1" placeholder="Ex: 5000">
                                            </div>
                                            <div class="col-md-3">
                                                <button type="button" class="btn btn-danger btn-sm btn-remove-dest" style="display:none;">Supprimer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <button type="button" class="btn btn-outline-success" id="btn-add-dest">+ Ajouter un destinataire</button>
                                <div class="text-end">
                                    <strong>Total débité : <span id="total-debit">0</span> AR</strong>
                                    <br><small id="total-detail"></small>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-info w-100" id="btn-submit">Effectuer l'envoi multiple</button>
                            <a href="/client/solde" class="btn btn-secondary w-100 mt-2">Annuler</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const prefixes = <?= json_encode(
            (new App\Models\PrefixesModel())->orderBy('code', 'ASC')->findAll()
        ) ?>;
        const ownOperateur = <?= json_encode($ownOperateur) ?>;
        let rowIndex = 1;

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

        function updateRemoveButtons() {
            const rows = document.querySelectorAll('.destinataire-row');
            rows.forEach((row, idx) => {
                const btn = row.querySelector('.btn-remove-dest');
                if (btn) {
                    btn.style.display = rows.length > 1 ? 'inline-block' : 'none';
                }
            });
        }

        function recalcTotal() {
            let total = 0;
            document.querySelectorAll('.destinataire-row').forEach(row => {
                const montant = parseFloat(row.querySelector('.montant-input').value) || 0;
                const tel = row.querySelector('.telephone-input').value.trim();
                const dest = detectOperateur(tel);
                let fraisFixe = 0;
                if (montant > 0) {
                    if (montant <= 10000) fraisFixe = 200;
                    else if (montant <= 50000) fraisFixe = 500;
                    else if (montant <= 100000) fraisFixe = 1000;
                    else if (montant <= 500000) fraisFixe = 2000;
                    else fraisFixe = 4000;
                }
                let commission = 0;
                if (dest && dest.operateur !== ownOperateur && dest.commission_pct > 0) {
                    commission = montant * (dest.commission_pct / 100);
                }
                total += montant + fraisFixe + commission;
            });
            document.getElementById('total-debit').textContent = new Intl.NumberFormat('fr-FR').format(Math.round(total));
        }

        function bindRowEvents(row) {
            const telInput = row.querySelector('.telephone-input');
            const montantInput = row.querySelector('.montant-input');
            const removeBtn = row.querySelector('.btn-remove-dest');

            telInput.addEventListener('input', function() {
                const label = row.querySelector('.operateur-label');
                const dest = detectOperateur(this.value.trim());
                if (dest) {
                    if (dest.operateur === ownOperateur) {
                        label.textContent = 'Opérateur : ' + dest.operateur + ' (même opérateur)';
                        label.className = 'form-text operateur-label text-success';
                    } else {
                        label.textContent = 'Opérateur : ' + dest.operateur + ' (AUTRE opérateur - non autorisé)';
                        label.className = 'form-text operateur-label text-danger';
                    }
                } else {
                    label.textContent = '';
                }
                recalcTotal();
            });

            montantInput.addEventListener('input', recalcTotal);

            if (removeBtn) {
                removeBtn.addEventListener('click', function() {
                    row.remove();
                    updateRemoveButtons();
                    recalcTotal();
                });
            }
        }

        document.getElementById('btn-add-dest').addEventListener('click', function() {
            const container = document.getElementById('destinataires-container');
            const template = `
                <div class="destinataire-row card mb-3" data-index="${rowIndex}">
                    <div class="card-body">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label">Numéro destinataire</label>
                                <div class="input-group">
                                    <span class="input-group-text">+243</span>
                                    <input type="tel" class="form-control telephone-input" name="destinataires[${rowIndex}][telephone]"
                                           required placeholder="0331234567" pattern="[0-9]{10,15}" maxlength="15">
                                </div>
                                <div class="form-text operateur-label text-info"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Montant (AR)</label>
                                <input type="number" class="form-control montant-input" name="destinataires[${rowIndex}][montant]"
                                       required min="1" step="1" placeholder="Ex: 5000">
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-danger btn-sm btn-remove-dest">Supprimer</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            const div = document.createElement('div');
            div.innerHTML = template;
            const newRow = div.firstElementChild;
            container.appendChild(newRow);
            bindRowEvents(newRow);
            updateRemoveButtons();
            rowIndex++;
        });

        document.querySelectorAll('.destinataire-row').forEach(bindRowEvents);
        updateRemoveButtons();

        document.getElementById('form-multiple').addEventListener('submit', function(e) {
            const rows = document.querySelectorAll('.destinataire-row');
            const opSet = new Set();
            let invalid = false;

            rows.forEach(row => {
                const tel = row.querySelector('.telephone-input').value.trim();
                const montant = parseFloat(row.querySelector('.montant-input').value) || 0;
                if (tel.length >= 3 && montant > 0) {
                    const dest = detectOperateur(tel);
                    if (dest) opSet.add(dest.operateur);
                }
            });

            if (opSet.size > 1) {
                e.preventDefault();
                alert('Tous les destinataires doivent appartenir au même opérateur.');
                return;
            }

            if (opSet.size === 1 && !opSet.has(ownOperateur)) {
                e.preventDefault();
                alert('Les destinataires doivent appartenir à votre opérateur (' + ownOperateur + ').');
                return;
            }
        });
    </script>
</body>
</html>
