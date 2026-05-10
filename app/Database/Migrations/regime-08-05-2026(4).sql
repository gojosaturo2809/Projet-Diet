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