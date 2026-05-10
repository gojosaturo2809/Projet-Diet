<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Export régimes</title>
<style>
body{font-family:Arial,Helvetica,sans-serif;color:#222;margin:20px}
.card{border:1px solid #ddd;padding:16px;margin-bottom:12px;border-radius:6px}
.header{display:flex;justify-content:space-between;align-items:center}
.h1{font-size:20px}
.meta{font-size:13px;color:#555}
@media print{.no-print{display:none}}
</style>
</head>
<body>
<div class="header">
    <div>
        <div class="h1">NutriPlan — Export des régimes</div>
        <div class="meta">Imprimé le <?= date('d/m/Y H:i') ?></div>
    </div>
    <div class="no-print"><button onclick="window.print()">Imprimer / Enregistrer en PDF</button></div>
</div>

<?php foreach (($regimes ?? []) as $r): ?>
    <div class="card">
        <h2><?= esc($r['nom'] ?? '—') ?></h2>
        <p><?= esc($r['description'] ?? '') ?></p>
        <div>Composition: Viande <?= esc($r['pourcentage_viande'] ?? '—') ?>% — Poisson <?= esc($r['pourcentage_poisson'] ?? '—') ?>% — Volaille <?= esc($r['pourcentage_volaille'] ?? '—') ?>%</div>
        <div style="margin-top:.6rem">Prix journalier: <?= number_format((float) ($r['prix_journalier'] ?? 0), 0, ',', ' ') ?> Ar</div>
        <div>Durée: <?= (int) ($semaines ?? 4) ?> semaines — Prix total estimé: <?php
            $jours = ((int) ($semaines ?? 4)) * 7;
            $prixBase = (float) ($r['prix_journalier'] ?? 0) * $jours;
            $prixFinal = (!empty($isGold)) ? round($prixBase * 0.85, 0) : round($prixBase, 0);
            echo number_format($prixFinal, 0, ',', ' ') . ' Ar';
        ?></div>
    </div>
<?php endforeach; ?>

</body>
</html>