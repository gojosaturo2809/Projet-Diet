-- Fichier: regime-08-05-2026(7).sql
-- Génération de données d'exemple avec prix logiques et associations sport-régime

-- 1. Vérification et insertion des régimes supplémentaires (si régimes basiques pas là)
-- Insérer uniquement si la table est vide ou pour ajout
INSERT INTO regimes (nom, description, prix_journalier, variation_poids_hebdo, poids_min_requis) VALUES
('Régime Protéiné Intensif', 'Riche en protéines pour la perte de poids avec performance sportive.', 16.50, -0.8, 60.0),
('Nutrition Équilibrée Premium', 'Régime premium avec consultation nutritionniste incluse.', 20.00, -0.5, 50.0),
('Prise Musculaire Avancée', 'Programme spécial pour la prise de muscle avec suppléments.',  22.50, 0.8, 55.0),
('Détox & Bien-être', 'Nettoyage du corps avec aliments biologiques et naturels.', 18.75, -0.3, 45.0);

-- 2. Composition pour les nouveaux régimes (IDs 4, 5, 6, 7)
INSERT INTO regime_composition (id_regime, pourcentage_viande, pourcentage_poisson, pourcentage_volaille) VALUES
(4, 45, 35, 20), -- Protéiné Intensif : priorité viande et poisson
(5, 30, 35, 35), -- Équilibrée Premium : équilibré
(6, 55, 15, 30), -- Prise Musculaire : beaucoup de viande
(7, 10, 40, 50); -- Détox : léger, plutôt poisson et volaille

-- 3. Mise à jour des associations régime-sport (si colonne id_regime_associe existe)
-- Associer des sports aux régimes (remplacer les NULL par des IDs de régimes pertinents)
UPDATE sports SET id_regime_associe = 1 WHERE nom = 'Marche rapide';
UPDATE sports SET id_regime_associe = 1 WHERE nom = 'Yoga';
UPDATE sports SET id_regime_associe = 2 WHERE nom = 'Natation';
UPDATE sports SET id_regime_associe = 2 WHERE nom = 'Cyclisme';
UPDATE sports SET id_regime_associe = 3 WHERE nom = 'Crossfit';
UPDATE sports SET id_regime_associe = 3 WHERE nom = 'Course à pied (Running)';
UPDATE sports SET id_regime_associe = 4 WHERE nom IN ('Marche rapide', 'Natation');
UPDATE sports SET id_regime_associe = 5 WHERE nom = 'Natation';
UPDATE sports SET id_regime_associe = 6 WHERE nom IN ('Crossfit', 'Course à pied (Running)');
UPDATE sports SET id_regime_associe = 7 WHERE nom IN ('Yoga', 'Natation');
