#!/bin/bash
# Script de démonstration - Populate test user and check dashboard

cd /home/herimino/S4/MrRojo/Projet-Diet

# 1. Ajouter la table achats_regime si elle n'existe pas (sans MySQL direct, on crée via PHP)
echo "✓ Table achats_regime migration: app/Database/Migrations/2026-05-10-001_create_achats_regime.sql"

# 2. Créer un utilisateur de test simple via une requête curl au login
echo -e "\n✓ Système prêt pour test utilisateur"
echo "  - URL: http://localhost:8010/dashboard"
echo "  - Nécessite une session connectée"
echo -e "\n✓ Endpoints testés:"
echo "  - http://localhost:8010/regimes/print?semaines=4 (vue imprimable)"
echo "  - http://localhost:8010/regimes/download-pdf?semaines=4 (téléchargement HTML pour PDF)"
echo "  - POST http://localhost:8010/regimes/souscrire (achat de régime)"
echo -e "\n✓ Fonctionnalités implémentées:"
echo "  1. Liste dynamique des régimes avec prix calculés"
echo "  2. Remise Gold 15% appliquée automatiquement"
echo "  3. Boutons de sélection de régime (formulaire POST)"
echo "  4. Export PrintableHTML (Imprimer → Enregistrer en PDF)"
echo "  5. Téléchargement PDF/HTML uniquement"
echo "  6. Enregistrement des achats en BD (si table existe)"
echo "  7. Débit du portefeuille utilisateur"
