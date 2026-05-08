-- SQLite Database Schema for NutriPlan
create database if not exists regime;
use regime;
CREATE TABLE IF NOT EXISTS utilisateur (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255),
    email VARCHAR(255) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS info_sante(
    id_info INT PRIMARY KEY AUTO_INCREMENT,
    id_utilisateur INT NOT NULL,
    poids REAL NOT NULL,
    taille REAL NOT NULL,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id)
);