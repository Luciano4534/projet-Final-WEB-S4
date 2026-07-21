-- =====================================================
-- Base de données : Système Mobile Money — Version 2
-- Projet Final S4
-- =====================================================

DROP TABLE IF EXISTS transactions;
DROP TABLE IF EXISTS baremes;
DROP TABLE IF EXISTS clients;
DROP TABLE IF EXISTS types_operations;
DROP TABLE IF EXISTS prefixes;

CREATE TABLE prefixes (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    code            TEXT    NOT NULL UNIQUE,
    operateur       TEXT    NOT NULL DEFAULT '',
    commission_pct  REAL    NOT NULL DEFAULT 0,
    created_at      TEXT    DEFAULT (datetime('now')),
    updated_at      TEXT    DEFAULT (datetime('now'))
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
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    nom             TEXT    NOT NULL,
    prenom          TEXT    NOT NULL,
    telephone       TEXT    NOT NULL UNIQUE,
    role            TEXT    NOT NULL DEFAULT 'client',
    solde           REAL    NOT NULL DEFAULT 0.00,
    credit_retrait  REAL    NOT NULL DEFAULT 0.00,
    created_at      TEXT    DEFAULT (datetime('now')),
    updated_at      TEXT    DEFAULT (datetime('now'))
);

CREATE TABLE settings (
     cle           TEXT    PRIMARY KEY,
     valeur        TEXT    NOT NULL DEFAULT '',
     created_at    TEXT    DEFAULT (datetime('now')),
     updated_at      TEXT    DEFAULT (datetime('now'))
);

CREATE TABLE transactions (
    id                      INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id               INTEGER NOT NULL,
    type_operation_id       INTEGER NOT NULL,
    montant                 REAL    NOT NULL,
    frais                   REAL    NOT NULL DEFAULT 0.00,
    frais_commission        REAL    NOT NULL DEFAULT 0.00,
    frais_retrait_inclus    REAL    NOT NULL DEFAULT 0.00,
    telephone_dest          TEXT,
    operateur_dest          TEXT    NOT NULL DEFAULT '',
    created_at              TEXT    DEFAULT (datetime('now')),
    updated_at              TEXT    DEFAULT (datetime('now')),
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (type_operation_id) REFERENCES types_operations(id)
);

-- =====================================================
-- Seeds
-- =====================================================

INSERT INTO prefixes (code, operateur, commission_pct) VALUES
    ('034', 'Yas', 1),
    ('038', 'Yas-New', 1),
    ('032', 'Orange-Money', 3),
    ('037', 'Orange-Money', 3);

INSERT INTO types_operations (libelle, description) VALUES
    ('Dépôt', 'Dépôt d''argent sur un compte'),
    ('Retrait', 'Retrait d''argent d''un compte'),
    ('Transfert', 'Transfert d''argent vers un autre compte');

INSERT INTO baremes (type_operation_id, montant_min, montant_max, frais) VALUES
    (1, 100, 1000, 50), (1, 1001, 5000, 50), (1, 5001, 10000, 100), (1, 10001, 25000, 200), (1, 25001, 50000, 400),
    (1, 50001, 100000, 800), (1, 100001, 250000, 1500), (1, 250001, 500000, 1500), (1, 500001, 1000000, 2500), (1, 1000001, 2000000, 3000),
    (2, 100, 1000, 50), (2, 1001, 5000, 50), (2, 5001, 10000, 100), (2, 10001, 25000, 200), (2, 25001, 50000, 400),
    (2, 50001, 100000, 800), (2, 100001, 250000, 1500), (2, 250001, 500000, 1500), (2, 500001, 1000000, 2500), (2, 1000001, 2000000, 3000),
    (3, 100, 1000, 50), (3, 1001, 5000, 50), (3, 5001, 10000, 100), (3, 10001, 25000, 200), (3, 25001, 50000, 400),
    (3, 50001, 100000, 800), (3, 100001, 250000, 1500), (3, 250001, 500000, 1500), (3, 500001, 1000000, 2500), (3, 1000001, 2000000, 3000);

-- Admin account
INSERT INTO clients (nom, prenom, telephone, role, solde) VALUES
    ('Admin', 'Super', '0330000000', 'admin', 0);
