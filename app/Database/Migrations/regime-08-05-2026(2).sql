-- MySQL Database Schema for NutriPlan
CREATE DATABASE IF NOT EXISTS regime;
USE regime;

CREATE TABLE IF NOT EXISTS utilisateur (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255),
    email VARCHAR(255) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS info_sante (
    id_info INT PRIMARY KEY AUTO_INCREMENT,
    id_utilisateur INT NOT NULL,
    poids DECIMAL(6,2) NOT NULL,
    taille DECIMAL(6,2) NOT NULL,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id)
);

-- Wallet / Gold fields used by WalletModel and GoldController
ALTER TABLE utilisateur
    ADD COLUMN IF NOT EXISTS solde DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    ADD COLUMN IF NOT EXISTS is_gold TINYINT(1) NOT NULL DEFAULT 0;

-- Recharge codes used by CodeModel
CREATE TABLE IF NOT EXISTS code_recharge (
    id INT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(80) NOT NULL UNIQUE,
    montant DECIMAL(12,2) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    used_by INT NULL,
    used_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_code_recharge_user FOREIGN KEY (used_by) REFERENCES utilisateur(id)
);

CREATE INDEX idx_code_recharge_active ON code_recharge(is_active);
CREATE INDEX idx_code_recharge_used_by ON code_recharge(used_by);

-- Optional SQL view for wallet display pages
CREATE OR REPLACE VIEW v_wallet_utilisateur AS
SELECT
    u.id,
    u.nom,
    u.prenom,
    u.email,
    u.solde,
    u.is_gold,
    CASE
        WHEN u.is_gold = 1 THEN 'Gold'
        ELSE 'Standard'
    END AS statut_abonnement
FROM utilisateur u;
