-- Ajouter colonne statut pour gérer la confirmation admin
-- Valeurs possibles: 'en_attente', 'confirmé', 'rejeté'
ALTER TABLE achats_regime ADD COLUMN statut VARCHAR(50) DEFAULT 'en_attente' AFTER semaines;
ALTER TABLE achats_regime ADD COLUMN date_confirmation DATETIME NULL AFTER statut;
ALTER TABLE achats_regime ADD COLUMN motif_rejet TEXT NULL AFTER date_confirmation;
ALTER TABLE achats_regime ADD INDEX idx_statut (statut);
