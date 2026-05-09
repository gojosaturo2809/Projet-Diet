# PROJET S4

## Concept général

- Groupe de 3 (Mixte fille/garçon)
- À rendre ce lundi 11 mai 2026
- Livraison :
  - Formulaire de livraison google forms
  - Lien code source gitlab ou github
  - script SQL base
  - Liste des membres groupes
  - Liste google sheet pour le suivi des taches
- Les commits et push doivent être effectués tout au long du projet, et non pas à la fin seulement
- La branche Main sera la branche de référence, il faut un merge dedans

## Mise en place d’une application pour sélectionner un régime alimentaire adapté selon ses objectifs

L’utilisateur entre ses informations (genre, taille, poids)

Le système affiche son indice de masse corporelle (IMC)

## Front Office

### Fonctionnalités minimum :

- Inscription et login
- Lors de l’inscription, il faut séparer en 2 pages différents les informations de l’utilisateur (nom, email, genre, …) et les informations de santé (taille, poids)
- Complétion du profil de l’utilisateur
- L’utilisateur peut choisir 3 objectifs :
  - Augmenter son poids
  - Réduire son poids
  - Atteindre son IMC idéal
- L’application suggère les régimes et l’activité sportive nécessaire pendant une durée
- On peut exporter sur PDF
- On peut rajouter de l’argent dans son porte monnaie en rentrant un code

### Fonctionnalités minimum (suite) :

- L’utilisateur peut avoir une option Gold qu’il va payer en une seule fois, à vous de proposer le prix et le mode d’accès
- Avec l’option Gold, l’utilisateur va avoir 15% de remise sur tous les régimes.

## Page d'authentification au démarrage

Tableau de bord, statistiques (mettre des graphes, des tableaux croisés, ...)

## Fonctionnalités

- CRUD des régimes
  - Prix variant selon la durée
  - Chaque régime permet de varier le poids pendant une durée (en plus et en moins)
- CRUD des activités sportives
- Validation des codes pour le porte monnaie des utilisateurs
- CRUD des paramètres nécessaires

## Détails complémentaires

- Pour constituer un régime, on doit mettre le % de viande, % de poisson, % de volaille

## Technologies imposées

- PHP + Framework Codeigniter
- HTML / CSS
- Javascript, AJAX
- Mysql ou postgres

## Données minimales

- 5 utilisateurs
- 15 codes
- 5 régimes
- 5 activités sportives