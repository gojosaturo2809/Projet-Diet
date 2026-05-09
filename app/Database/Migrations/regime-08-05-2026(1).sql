-- SQLite Database Schema for NutriPlan
create database if not exists regime;
use regime;
CREATE TABLE IF NOT EXISTS utilisateur (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255),
    email VARCHAR(255) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    solde DECIMAL(12,2) DEFAULT 0.00,
    is_gold TINYINT(1) DEFAULT 0
);

CREATE TABLE IF NOT EXISTS info_sante(
    id_info INT PRIMARY KEY AUTO_INCREMENT,
    id_utilisateur INT NOT NULL,
    poids REAL NOT NULL,
    taille REAL NOT NULL,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id)
);

ALTER TABLE info_sante ADD COLUMN IF NOT EXISTS poids REAL NOT NULL;
ALTER TABLE info_sante ADD COLUMN IF NOT EXISTS taille REAL NOT NULL;
