-- Table objctif
CREATE TABLE objectifs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- Table d'associotion entre les utilisateurs et les objectifs
CREATE TABLE objectifs_utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT NOT NULL,
    id_objectif INT NOT NULL,
    date_debut DATETIME DEFAULT CURRENT_TIMESTAMP,
    actif BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id) ON DELETE CASCADE,
    FOREIGN KEY (id_objectif) REFERENCES objectifs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

insert into objectifs (nom) values ('Perte de poids');
insert into objectifs (nom) values ('Prise de muscle');
insert into objectifs (nom) values ('Maintien du poids');

ALTER TABLE objectifs_utilisateur 
ADD COLUMN poids_cible REAL NOT NULL,      -- Le poids que l'utilisateur veut atteindre
ADD COLUMN duree_objectif_semaine INT NOT NULL, -- La durée (convertie en semaines pour les calculs)
ADD COLUMN ecart_poids REAL;              -- Différence entre poids actuel et cible (ex: -5 ou +3)