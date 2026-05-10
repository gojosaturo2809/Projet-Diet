-- Créer la table achats_regime si elle n'existe pas
CREATE TABLE IF NOT EXISTS achats_regime (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_utilisateur INT NOT NULL,
  id_regime INT NOT NULL,
  prix_paye DECIMAL(10, 2) NOT NULL,
  remise_appliquee DECIMAL(10, 2) DEFAULT 0,
  semaines INT NOT NULL DEFAULT 1,
  date_achat DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id) ON DELETE CASCADE,
  FOREIGN KEY (id_regime) REFERENCES regimes(id) ON DELETE CASCADE,
  INDEX idx_utilisateur (id_utilisateur),
  INDEX idx_regime (id_regime),
  INDEX idx_date (date_achat)
);
