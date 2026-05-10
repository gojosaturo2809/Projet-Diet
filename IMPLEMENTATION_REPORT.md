# 🍽️ NutriPlan — Système de Recommandation de Régimes

## 📊 Améliorations Implémentées (10/05/2026)

### 1️⃣ **Liste Dynamique des Régimes avec Sélection**
- **Route**: `dashboard` (section "Régimes disponibles")
- **Affichage**: Grille responsive avec tous les régimes disponibles
- **Informations affichées**:
  - Nom et description du régime
  - Composition (% Viande, Poisson, Volaille)
  - Prix journalier
  - Prix total pour la durée de l'objectif
  - Sport suggéré selon l'intensité du régime
- **Action**: Bouton "Choisir ce régime" pour souscrire

### 2️⃣ **Calcul du Prix Dynamique**
- Base: `prix_journalier × nombre_de_jours`
- Remise Gold: **15% réduction** appliquée automatiquement
- Exemple: 
  - Base: 84 Ar/jour × 28 jours = 2,352 Ar
  - Gold: 2,352 × 0.85 = 1,999 Ar (économie: 353 Ar)

### 3️⃣ **Souscription et Débit Portefeuille**
- **Endpoint**: `POST /regimes/souscrire`
- **Actions**:
  1. Vérification de la session utilisateur
  2. Validation du solde portefeuille
  3. Débit du montant (prix final avec remise Gold)
  4. Enregistrement de l'achat en base de données (`achats_regime`)
  5. Affichage d'un message de confirmation avec détails
  6. Redirection vers le dashboard

- **Modèle**: `AchatModel` — gère les transactions
  ```php
  recordAchat($userId, $regimeId, $prixPaye, $remise, $semaines)
  ```

### 4️⃣ **Export et Impression en PDF**

#### Option 1: Vue Imprimable
- **Route**: `GET /regimes/print?semaines=4`
- **Utilisation**: Imprimer → Enregistrer en PDF (navigateur)
- **Contenu**: Liste complète ou régime unique

#### Option 2: Téléchargement HTML Optimisé
- **Route**: `GET /regimes/download-pdf?semaines=4`
- **Utilisation**: Télécharge un fichier `.html` formaté pour impression
- **Styles print**: Optimisé pour PDF (marges, sauts de page, pagination)
- **Contenu généré**:
  - En-tête NutriPlan avec date/heure
  - Fiche détaillée par régime
  - Composition alimentaire
  - Tarification avec remise Gold si applicable

### 5️⃣ **Modèles et Contrôleurs**

#### `RegimeModel` (amélioré)
```php
getAllWithComposition()      // Récupère tous les régimes avec composition
suggererRegime($vitesseRequise) // Suggestion basée sur objectif
getSportSuggere($variation)     // Sport recommandé
```

#### `AchatModel` (nouveaux)
```php
recordAchat($userId, $regimeId, $prixPaye, $remise, $semaines)
getAchatsUtilisateur($userId)
```

#### `RegimeController` (amélioré)
```php
souscrire()         // POST: Traiter l'achat et débiter portefeuille
printable()         // GET: Vue HTML imprimable
downloadPdf()       // GET: Télécharger HTML formaté pour PDF
generatePdfHtml()   // Générer le HTML optimisé
```

### 6️⃣ **Database**
- **Table**: `achats_regime` — journalise les achats
  - `id_utilisateur` → `utilisateur.id`
  - `id_regime` → `regimes.id`
  - `prix_paye`, `remise_appliquee`, `semaines`
  - `date_achat` (timestamp automatique)
  - Indexes sur utilisateur, régime, date

**Migration SQL**: `app/Database/Migrations/2026-05-10-001_create_achats_regime.sql`

### 7️⃣ **Routes Ajoutées**
```php
GET   /regimes/print              → RegimeController::printable()
GET   /regimes/download-pdf       → RegimeController::downloadPdf()
POST  /regimes/souscrire          → RegimeController::souscrire()
```

### 8️⃣ **Vue Dashboard Dynamique**
- Section "Gérer mon régime" → Liste clickable avec achat instantané
- Section "Mes activités" → Sports recommandés selon objectif
- Confirmation Flash → Message succès avec montant débité

---

## 🧪 Testing & Validation

### Tests Effectués ✓
- ✅ Syntaxe PHP vérifiée (lint)
- ✅ Endpoint `/regimes/download-pdf` — Retourne HTML optimisé (5,3 KB)
- ✅ Endpoint `/regimes/print` — Affichage imprimable
- ✅ Serveur CodeIgniter 4 actif sur port 8010
- ✅ Modèles et contrôleurs loadent sans erreur

### Pour Tester Localement

1. **Connectez-vous**: http://localhost:8010/login
   - Email: (compte existant)
   - Mot de passe: (mot de passe du compte)

2. **Accédez au Dashboard**: http://localhost:8010/dashboard

3. **Testez l'export**: http://localhost:8010/regimes/download-pdf?semaines=6
   - Télécharge un fichier HTML
   - Ouvrez dans navigateur et testez Print → Save as PDF

4. **Testez l'achat**: Cliquez "Choisir ce régime"
   - Débite le solde portefeuille
   - Enregistre l'achat en base
   - Affiche confirmation avec montant

---

## 📝 Notes d'Implémentation

- **Sans Dompdf**: Approche légère basée sur HTML+CSS print-friendly
- **Remise Gold**: Appliquée au moment de la souscription et visible dans les fiches PDF
- **Sécurité**: CSRF token sur formulaire POST, sessions vérifiées
- **Responsif**: Grille de régimes adaptée aux mobile/desktop

---

## 🔧 Fichiers Modifiés

```
app/
  Models/
    ├── RegimeModel.php (+ méthode getAllWithComposition)
    └── AchatModel.php (NOUVEAU)
  Controllers/
    ├── RegimeController.php (+ souscrire, printable, downloadPdf, generatePdfHtml)
    └── Dashboard.php (+ regimesList, regime_weeks, suggested_sport)
  Views/
    ├── dashboard.php (+ section régimes)
    └── regimes/printable.php (NOUVEAU)
  Config/
    └── Routes.php (+ 3 routes régimes)
  Database/
    └── Migrations/2026-05-10-001_create_achats_regime.sql (NOUVEAU)
```

---

**Déploiement**: Importer la migration SQL et tester via le navigateur ! 🚀
