<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Money - Bienvenue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .welcome-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            border: none;
        }
        .welcome-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3) !important;
        }
        .icon-circle {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 48px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="display-3 text-white fw-bold">Mobile Money</h1>
            <p class="lead text-white-50">Système de paiement mobile</p>
        </div>

        <div class="row justify-content-center g-4">
            <!-- Espace Client -->
            <div class="col-md-5">
                <a href="/login" class="text-decoration-none">
                    <div class="card welcome-card shadow h-100">
                        <div class="card-body text-center p-5">
                            <div class="icon-circle bg-success bg-gradient text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H2s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                                </svg>
                            </div>
                            <h2 class="card-title text-success fw-bold mb-3">Espace Client</h2>
                            <p class="card-text text-muted">
<<<<<<< HEAD
                                Consultez votre solde, effectuez des dépôts, retraits et transferts.
=======
                                Connectez-vous avec votre numéro de téléphone et gérez vos transactions.
>>>>>>> student/luciano
                            </p>
                            <div class="mt-4">
                                <span class="btn btn-success btn-lg px-5">Se connecter</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Espace Opérateur -->
            <div class="col-md-5">
<<<<<<< HEAD
                <a href="/dashboard" class="text-decoration-none">
=======
                <a href="/prefixes" class="text-decoration-none">
>>>>>>> student/luciano
                    <div class="card welcome-card shadow h-100">
                        <div class="card-body text-center p-5">
                            <div class="icon-circle bg-primary bg-gradient text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M0 0h1v15h15v1H0zm14.817 3.113a.5.5 0 0 1 .07.704l-4.5 5.5a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61 4.15-5.073a.5.5 0 0 1 .704-.07"/>
                                </svg>
                            </div>
                            <h2 class="card-title text-primary fw-bold mb-3">Espace Opérateur</h2>
                            <p class="card-text text-muted">
<<<<<<< HEAD
                                Gérez les comptes clients, les barèmes et consultez les statistiques.
=======
                                Gérez les préfixes et les types d'opérations de l'opérateur.
>>>>>>> student/luciano
                            </p>
                            <div class="mt-4">
                                <span class="btn btn-primary btn-lg px-5">Accéder</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
