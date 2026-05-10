-- Migration pour enrichir la table regimes avec descriptions détaillées
-- À exécuter: mysql regime < this_file.sql

ALTER TABLE regimes ADD COLUMN IF NOT EXISTS description_detaillee LONGTEXT AFTER description;

-- Mise à jour avec descriptions enrichies pour chaque régime
UPDATE regimes SET description_detaillee = 
"<h2>Programme Éclair - Perte Rapide</h2>
<p>Conçu pour une perte de poids rapide et motivante, ce régime utilise une approche hypocalorique équilibrée.</p>

<h3>Caractéristiques</h3>
<ul>
  <li><strong>Variation de poids:</strong> -0.5 kg par semaine (environ -2 kg par mois)</li>
  <li><strong>Composition:</strong> 20% Viande, 50% Poisson, 30% Volaille</li>
  <li><strong>Prix:</strong> 16 Ar par jour</li>
  <li><strong>Durée typique:</strong> 4 à 8 semaines</li>
</ul>

<h3>Principes Clés</h3>
<ul>
  <li>Protéines maigres (poisson principalement)</li>
  <li>Restriction calorique modérée (-500 kcal/jour)</li>
  <li>Activité sportive recommandée: Intense (Jogging, Musculation)</li>
</ul>

<h3>Aliments Recommandés</h3>
<table border=\"1\" cellpadding=\"5\">
  <tr><th>Catégorie</th><th>Exemples</th></tr>
  <tr><td>Poisson</td><td>Saumon, Truite, Morue (50% des protéines)</td></tr>
  <tr><td>Viande Maigre</td><td>Poulet sans peau, Dinde (20%)</td></tr>
  <tr><td>Volaille</td><td>Œufs, Blanc de poulet (30%)</td></tr>
  <tr><td>Fruits/Légumes</td><td>Brocoli, Épinards, Pommes, Baies</td></tr>
  <tr><td>Féculents</td><td>Riz complet, Pâtes complètes (modéré)</td></tr>
</table>

<h3>Programme Type (4 semaines)</h3>
<ul>
  <li><strong>Semaine 1-2:</strong> Adaptation métabolique - 1 kg de perte</li>
  <li><strong>Semaine 3-4:</strong> Accélération - 2 kg de perte</li>
</ul>

<h3>Conseils de Succès</h3>
<ul>
  <li>Boire 2-3L d'eau par jour</li>
  <li>Pratiquer 30-45min de sport intensif, 3-4 fois par semaine</li>
  <li>Dormir 7-8 heures pour optimiser le métabolisme</li>
  <li>Faire un suivi hebdomadaire du poids</li>
</ul>"
WHERE nom = "Programme Éclair (Perte)";

UPDATE regimes SET description_detaillee = 
"<h2>Équilibre & Vitalité - Maintien Durable</h2>
<p>Un régime équilibré pour stabiliser votre poids tout en restant en pleine forme et vitalité.</p>

<h3>Caractéristiques</h3>
<ul>
  <li><strong>Variation de poids:</strong> ±0 kg par semaine (maintien)</li>
  <li><strong>Composition:</strong> 33% Viande, 33% Poisson, 34% Volaille</li>
  <li><strong>Prix:</strong> 12 Ar par jour</li>
  <li><strong>Durée typique:</strong> Indéfini (style de vie)</li>
</ul>

<h3>Principes Clés</h3>
<ul>
  <li>Équilibre alimentaire parfait (tiers-tiers-tiers)</li>
  <li>Apport calorique = Dépense énergétique</li>
  <li>Activité sportive recommandée: Modérée (Marche, Yoga, Natation)</li>
</ul>

<h3>Aliments Recommandés</h3>
<table border=\"1\" cellpadding=\"5\">
  <tr><th>Catégorie</th><th>Exemples</th></tr>
  <tr><td>Viande (33%)</td><td>Boeuf maigre, Agneau, Porc</td></tr>
  <tr><td>Poisson (33%)</td><td>Thon, Sardine, Lieu</td></tr>
  <tr><td>Volaille (34%)</td><td>Poulet, Dinde, Œufs</td></tr>
  <tr><td>Fruits/Légumes</td><td>Tous les types - illimité</td></tr>
  <tr><td>Féculents</td><td>Pain complet, Riz, Pommes de terre</td></tr>
</table>

<h3>Plan Hebdomadaire</h3>
<ul>
  <li><strong>Lundi-Mercredi:</strong> 3 portions de viande</li>
  <li><strong>Jeudi-Samedi:</strong> 3 portions de poisson</li>
  <li><strong>Dimanche:</strong> Combiné ou choix libre</li>
</ul>

<h3>Conseils de Longévité</h3>
<ul>
  <li>Apportée un équilibre nutritionnel complet</li>
  <li>Varié les sources de protéines</li>
  <li>Pratiquer 30min d'activité modérée, 5 fois par semaine</li>
  <li>Contrôles réguliers de santé (3-6 mois)</li>
</ul>"
WHERE nom = "Équilibre & Vitalité";

UPDATE regimes SET description_detaillee = 
"<h2>Mass Gain Plus - Prise de Masse Musculaire</h2>
<p>Un régime dédié à l'augmentation de masse musculaire et au gain de poids sain et contrôlé.</p>

<h3>Caractéristiques</h3>
<ul>
  <li><strong>Variation de poids:</strong> +0.5 kg par semaine (environ +2 kg par mois)</li>
  <li><strong>Composition:</strong> 50% Viande, 10% Poisson, 40% Volaille</li>
  <li><strong>Prix:</strong> 18 Ar par jour</li>
  <li><strong>Durée typique:</strong> 8-12 semaines</li>
</ul>

<h3>Principes Clés</h3>
<ul>
  <li>Surplus calorique modéré (+500 kcal/jour)</li>
  <li>Protéines maximales (200-250g/jour)</li>
  <li>Activité sportive recommandée: Intense (Musculation, Force)</li>
</ul>

<h3>Aliments Recommandés</h3>
<table border=\"1\" cellpadding=\"5\">
  <tr><th>Catégorie</th><th>Exemples</th></tr>
  <tr><td>Viande (50%)</td><td>Boeuf rouge, Agneau, Porc gras (riche)</td></tr>
  <tr><td>Volaille (40%)</td><td>Poulet fermier, Œufs entiers, Dinde</td></tr>
  <tr><td>Poisson (10%)</td><td>Saumon, Maquereau</td></tr>
  <tr><td>Féculents</td><td>Riz blanc, Pâtes, Patates douces</td></tr>
  <tr><td>Produits Laitiers</td><td>Lait entier, Fromage, Yaourt gras</td></tr>
</table>

<h3>Progression Hebdomadaire</h3>
<ul>
  <li><strong>Semaines 1-4:</strong> Prise progressive - +0.5 kg</li>
  <li><strong>Semaines 5-8:</strong> Consolidation musculaire - +1 kg</li>
  <li><strong>Semaines 9-12:</strong> Finalisation - +1.5 kg minimum</li>
</ul>

<h3>Conseils pour Optimiser la Masse</h3>
<ul>
  <li>Musculation intensive (4-5 jours par semaine)</li>
  <li>Repos adéquat (8-9 heures de sommeil)</li>
  <li>Supplémentation en créatine et protéine</li>
  <li>Suivi régulier de composition corporelle (pas seulement le poids)</li>
</ul>"
WHERE nom = "Mass Gain Plus (Prise)";
