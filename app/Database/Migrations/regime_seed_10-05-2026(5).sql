-- 1. Insertion des Régimes
-- On définit des variations hebdomadaires réalistes (en kg/semaine)
INSERT INTO regimes (nom, description, prix_journalier, variation_poids_hebdo, poids_min_requis) VALUES
('Programme Éclair (Perte)', 'Un régime hypocalorique conçu pour une perte de poids rapide et motivante.', 15.50, -1.2, 55.0),
('Équilibre & Vitalité', 'Une approche modérée pour stabiliser son poids tout en restant en pleine forme.', 12.00, -0.4, 45.0),
('Mass Gain Plus (Prise)', 'Riche en protéines et calories pour favoriser la prise de masse musculaire.', 18.00, 0.6, 50.0);

-- 2. Insertion des Compositions (doit correspondre aux IDs des régimes ci-dessus)
-- On utilise les IDs 1, 2 et 3 (assurez-vous que la table était vide)
INSERT INTO regime_composition (id_regime, pourcentage_viande, pourcentage_poisson, pourcentage_volaille) VALUES
(1, 20, 50, 30), -- Régime Éclair : priorité au poisson (léger)
(2, 33, 33, 34), -- Équilibré : tiers partout
(3, 50, 10, 40); -- Prise : priorité viande rouge et volaille (protéines)

-- 3. Insertion des Sports
INSERT INTO sports (nom, intensite, calories_heure) VALUES
('Marche rapide', 'Faible', 250),
('Natation', 'Modérée', 500),
('Course à pied (Running)', 'Intense', 800),
('Cyclisme', 'Modérée', 450),
('Crossfit', 'Intense', 900),
('Yoga', 'Faible', 180);