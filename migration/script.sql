-- Création des types ENUM
CREATE TYPE type_compte AS ENUM ('principal', 'secondaire');
CREATE TYPE status_compte AS ENUM ('actif', 'bloqué', 'supprimé');

-- Création de la table compte
CREATE TABLE compte (
    id SERIAL PRIMARY KEY,
    numtel VARCHAR(20) NOT NULL UNIQUE,
    photocnirecto VARCHAR(255),
    photocniverso VARCHAR(255),
    client_id INTEGER NOT NULL,
    typedecompte type_compte DEFAULT 'principal',
    solde NUMERIC(15,2) DEFAULT 0.00,
    status status_compte DEFAULT 'actif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Clé étrangère vers client(id)
    CONSTRAINT fk_compte_client FOREIGN KEY (client_id) REFERENCES client(id) ON DELETE CASCADE
);

-- Création de la table client
CREATE TABLE client (
    id SERIAL PRIMARY KEY,
    prenom VARCHAR(100) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    cni VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Ajout des index secondaires (en plus des contraintes UNIQUE)
CREATE INDEX idx_client_cni ON client(cni);
CREATE INDEX idx_client_email ON client(email);


-- Création de la table transaction
CREATE TABLE transaction (
    id SERIAL PRIMARY KEY,
    expediteur_id INTEGER REFERENCES compte(id) ON DELETE CASCADE,
    destinataire_id INTEGER REFERENCES compte(id) ON DELETE CASCADE,
    montant NUMERIC(15,2) NOT NULL,
    type_transaction VARCHAR(20) DEFAULT 'paiement',
    description TEXT,
    statut VARCHAR(20) DEFAULT 'reussi',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_transaction_created_at ON transaction(created_at);
CREATE INDEX idx_transaction_expediteur ON transaction(expediteur_id);
CREATE INDEX idx_transaction_destinataire ON transaction(destinataire_id);
