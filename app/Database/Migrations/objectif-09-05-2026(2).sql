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
