-- Ajouter rôle
ALTER TABLE utilisateur
ADD role VARCHAR(20) DEFAULT 'user';

-- Admin user
INSERT INTO utilisateur (nom,email, mot_de_passe, role)
VALUES (
    'admin@gmail.com',
    '$2y$12$p3wQOUvYrjOs42jjtB54J.CnwpuY7ZSzqIXjJwtFxi1CJmikf4g.G',
    'admin'
);
Inser