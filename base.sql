Pour le recréer, tu peux remplacer le contenu actuel par celui-ci :
-- =====================================================
-- Base de données : Système Mobile Money
-- Projet Final S4
-- =====================================================

DROP TABLE IF EXISTS transactions;
DROP TABLE IF EXISTS baremes;
DROP TABLE IF EXISTS clients;
DROP TABLE IF EXISTS types_operations;
DROP TABLE IF EXISTS prefixes;

CREATE TABLE prefixes (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    code        TEXT    NOT NULL UNIQUE,
    created_at  TEXT    DEFAULT (datetime('now')),
    updated_at  TEXT    DEFAULT (datetime('now'))
);

CREATE TABLE types_operations (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle     TEXT    NOT NULL UNIQUE,
    description TEXT,
    created_at  TEXT    DEFAULT (datetime('now')),
    updated_at  TEXT    DEFAULT (datetime('now'))
);

CREATE TABLE baremes (
    id                  INTEGER PRIMARY KEY AUTOINCREMENT,
    type_operation_id   INTEGER NOT NULL,
    montant_min         REAL    NOT NULL,
    montant_max         REAL    NOT NULL,
    frais               REAL    NOT NULL,
    created_at          TEXT    DEFAULT (datetime('now')),
    updated_at          TEXT    DEFAULT (datetime('now')),
    FOREIGN KEY (type_operation_id) REFERENCES types_operations(id)
);

CREATE TABLE clients (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    nom         TEXT    NOT NULL,
    prenom      TEXT    NOT NULL,
    telephone   TEXT    NOT NULL UNIQUE,
    solde       REAL    NOT NULL DEFAULT 0.00,
    created_at  TEXT    DEFAULT (datetime('now')),
    updated_at  TEXT    DEFAULT (datetime('now'))
);

CREATE TABLE transactions (
    id                  INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id           INTEGER NOT NULL,
    type_operation_id   INTEGER NOT NULL,
    montant             REAL    NOT NULL,
    frais               REAL    NOT NULL DEFAULT 0.00,
    telephone_dest      TEXT,
    created_at          TEXT    DEFAULT (datetime('now')),
    updated_at          TEXT    DEFAULT (datetime('now')),
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (type_operation_id) REFERENCES types_operations(id)
);

INSERT INTO prefixes (code) VALUES
    ('033'), ('034'), ('037'),
    ('038');

INSERT INTO types_operations (libelle, description) VALUES
    ('Dépôt', 'Dépôt d''argent sur un compte'),
    ('Retrait', 'Retrait d''argent d''un compte'),
    ('Transfert', 'Transfert d''argent vers un autre compte');

INSERT INTO baremes (type_operation_id, montant_min, montant_max, frais) VALUES
    (1, 0, 10000, 100), (1, 10001, 50000, 250), (1, 50001, 100000, 500), (1, 100001, 500000, 1000), (1, 500001, 1000000, 2000),
    (2, 0, 10000, 150), (2, 10001, 50000, 350), (2, 50001, 100000, 700), (2, 100001, 500000, 1500), (2, 500001, 1000000, 3000),
    (3, 0, 10000, 200), (3, 10001, 50000, 500), (3, 50001, 100000, 1000), (3, 100001, 500000, 2000), (3, 500001, 1000000, 4000);