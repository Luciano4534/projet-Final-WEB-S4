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
    ('033', 'Vodacom', 0),
    ('034', 'Vodacom', 0),
    ('035', 'Vodacom', 0),
    ('036', 'Vodacom', 0),
    ('037', 'Vodacom', 0),
    ('038', 'Vodacom', 0),
    ('039', 'Vodacom', 0),
    ('050', 'Vodacom', 0),
    ('051', 'Vodacom', 0),
    ('052', 'Vodacom', 0),
    ('031', 'Airtel', 2.5),
    ('032', 'Orange', 3.0),
    ('099', 'MPesa', 2.0);

INSERT INTO types_operations (libelle, description) VALUES
    ('Dépôt', 'Dépôt d''argent sur un compte'),
    ('Retrait', 'Retrait d''argent d''un compte'),
    ('Transfert', 'Transfert d''argent vers un autre compte');

INSERT INTO baremes (type_operation_id, montant_min, montant_max, frais) VALUES
    (1, 0, 10000, 100), (1, 10001, 50000, 250), (1, 50001, 100000, 500), (1, 100001, 500000, 1000), (1, 500001, 1000000, 2000),
    (2, 0, 10000, 150), (2, 10001, 50000, 350), (2, 50001, 100000, 700), (2, 100001, 500000, 1500), (2, 500001, 1000000, 3000),
    (3, 0, 10000, 200), (3, 10001, 50000, 500), (3, 50001, 100000, 1000), (3, 100001, 500000, 2000), (3, 500001, 1000000, 4000);

-- Admin account
INSERT INTO clients (nom, prenom, telephone, role, solde) VALUES
    ('Admin', 'Super', '0330000000', 'admin', 0);
