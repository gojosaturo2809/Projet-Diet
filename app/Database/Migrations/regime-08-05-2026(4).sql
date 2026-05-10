-- 1. Table des régimes
CREATE TABLE regimes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    prix_journalier REAL NOT NULL,
    -- Variation de poids moyenne par semaine (ex: -0.5 pour perdre, +0.3 pour gagner)
    variation_poids_hebdo REAL NOT NULL, 
    poids_min_requis REAL DEFAULT 0, -- Pour éviter les régimes trop intenses
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Détails de la composition (en %)
CREATE TABLE regime_composition (
    id_composition INT PRIMARY KEY AUTO_INCREMENT,
    id_regime INT NOT NULL,
    pourcentage_viande INT DEFAULT 0,
    pourcentage_poisson INT DEFAULT 0,
    pourcentage_volaille INT DEFAULT 0,
    FOREIGN KEY (id_regime) REFERENCES regimes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Table des activités sportives suggérées
CREATE TABLE sports (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    intensite ENUM('Faible', 'Modérée', 'Intense') NOT NULL,
    calories_heure INT,
    id_regime_associe INT, -- Optionnel : associer un sport à un type de régime
    FOREIGN KEY (id_regime_associe) REFERENCES regimes(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des transactions d'achat de régime
CREATE TABLE IF NOT EXISTS achats_regime (
    id_achat INT PRIMARY KEY AUTO_INCREMENT,
    id_utilisateur INT NOT NULL,
    id_regime INT NOT NULL,
    date_achat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    poids_depart REAL NOT NULL,      -- Le poids de l'utilisateur au moment de l'achat
    poids_cible_vise REAL NOT NULL, -- L'objectif final au moment de l'achat
    duree_semaines INT NOT NULL,    -- La durée du programme acheté
    montant_total REAL NOT NULL,    -- Le prix final payé (après remise)
    est_gold BOOLEAN DEFAULT FALSE, -- Pour savoir si l'utilisateur était Gold lors de l'achat
    statut ENUM('en_cours', 'termine', 'annule') DEFAULT 'en_cours',
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id),
    FOREIGN KEY (id_regime) REFERENCES regimes(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;