<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($regime['nom'] ?? 'Détail Régime') ?> - NutriPlan</title>
    <link rel="stylesheet" href="<?= base_url('css/dashboard.css') ?>">
    <style>
        .detail-container { max-width: 900px; margin: 20px auto; padding: 20px; }
        .regime-header { margin-bottom: 30px; border-bottom: 2px solid #d32f2f; padding-bottom: 20px; }
        .regime-header h1 { color: #333; margin: 0 0 10px; font-size: 32px; }
        .regime-header p { color: #666; margin: 5px 0; font-size: 14px; }
        .regime-meta { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin: 20px 0; }
        .meta-card { background: #f5f5f5; padding: 15px; border-radius: 8px; border-left: 4px solid #d32f2f; }
        .meta-label { font-size: 12px; color: #999; text-transform: uppercase; font-weight: 600; }
        .meta-value { font-size: 18px; font-weight: bold; color: #333; margin-top: 5px; }
        .composition-section, .activites-section { margin: 30px 0; }
        .section-title { font-size: 18px; font-weight: 600; color: #333; margin-bottom: 15px; border-bottom: 1px solid #ddd; padding-bottom: 10px; }
        .composition-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; }
        .composition-item { text-align: center; background: #f9f9f9; padding: 20px; border-radius: 8px; }
        .comp-percentage { font-size: 28px; font-weight: bold; color: #d32f2f; }
        .comp-label { font-size: 13px; color: #666; margin-top: 10px; }
        .activites-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 15px; }
        .activite-card { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 15px; }
        .activite-name { font-size: 16px; font-weight: 600; color: #333; }
        .activite-intensite { display: inline-block; margin-top: 8px; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; }
        .intensite-faible { background: #e3f2fd; color: #1976d2; }
        .intensite-moderee { background: #fff3e0; color: #f57c00; }
        .intensite-intense { background: #ffebee; color: #c62828; }
        .activite-calories { font-size: 13px; color: #666; margin-top: 8px; }
        .no-activites { padding: 20px; text-align: center; color: #999; background: #f5f5f5; border-radius: 8px; }
        .action-buttons { margin: 30px 0; display: flex; gap: 10px; }
        .btn { padding: 10px 20px; border-radius: 6px; border: none; cursor: pointer; font-size: 14px; font-weight: 600; text-decoration: none; display: inline-block; }
        .btn-primary { background: #d32f2f; color: white; }
        .btn-primary:hover { background: #b71c1c; }
        .btn-secondary { background: #f5f5f5; color: #333; border: 1px solid #ddd; }
        .btn-secondary:hover { background: #eeeeee; }
        @media print {
            .action-buttons { display: none; }
            .regime-header { border-bottom: 1px solid #999; }
        }
    </style>
</head>
<body>
    <div class="detail-container">
        <div class="regime-header">
            <h1><?= esc($regime['nom'] ?? '—') ?></h1>
            <p><?= esc($regime['description'] ?? '') ?></p>
        </div>

        <div class="regime-meta">
            <div class="meta-card">
                <div class="meta-label">Prix Journalier</div>
                <div class="meta-value"><?= number_format((float) ($regime['prix_journalier'] ?? 0), 2, ',', ' ') ?> Ar</div>
            </div>
            <div class="meta-card">
                <div class="meta-label">Variation Hebdo</div>
                <div class="meta-value"><?= ((float) ($regime['variation_poids_hebdo'] ?? 0) >= 0 ? '+' : '') . number_format((float) ($regime['variation_poids_hebdo'] ?? 0), 2, ',', ' ') ?> kg</div>
            </div>
            <div class="meta-card">
                <div class="meta-label">Poids Min. Requis</div>
                <div class="meta-value"><?= number_format((float) ($regime['poids_min_requis'] ?? 0), 1, ',', ' ') ?> kg</div>
            </div>
        </div>

        <?php if (! empty($regime['pourcentage_viande']) || ! empty($regime['pourcentage_poisson']) || ! empty($regime['pourcentage_volaille'])) : ?>
        <div class="composition-section">
            <h3 class="section-title">Composition Alimentaire</h3>
            <div class="composition-grid">
                <div class="composition-item">
                    <div class="comp-percentage"><?= (int) ($regime['pourcentage_viande'] ?? 0) ?>%</div>
                    <div class="comp-label">Viande</div>
                </div>
                <div class="composition-item">
                    <div class="comp-percentage"><?= (int) ($regime['pourcentage_poisson'] ?? 0) ?>%</div>
                    <div class="comp-label">Poisson</div>
                </div>
                <div class="composition-item">
                    <div class="comp-percentage"><?= (int) ($regime['pourcentage_volaille'] ?? 0) ?>%</div>
                    <div class="comp-label">Volaille</div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="activites-section">
            <h3 class="section-title">Activités Recommandées</h3>
            <?php if (! empty($regime['activites']) && is_array($regime['activites'])) : ?>
                <div class="activites-grid">
                    <?php foreach ($regime['activites'] as $activite) : ?>
                        <div class="activite-card">
                            <div class="activite-name"><?= esc($activite['nom'] ?? '—') ?></div>
                            <div class="activite-intensite intensite-<?= strtolower(str_replace(' ', '_', $activite['intensite'] ?? 'moderee')) ?>">
                                <?= esc($activite['intensite'] ?? 'Modérée') ?>
                            </div>
                            <?php if (! empty($activite['calories_heure'])) : ?>
                                <div class="activite-calories">
                                    💪 <?= (int) $activite['calories_heure'] ?> cal/h
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="no-activites">
                    Aucune activité n'est recommandée pour ce régime actuellement.
                </div>
            <?php endif; ?>
        </div>

        <div class="action-buttons">
            <button class="btn btn-primary" onclick="window.print()">📄 Exporter en PDF</button>
            <a href="<?= site_url('regimes') ?>" class="btn btn-secondary">← Retour aux Régimes</a>
        </div>
    </div>

    <script>
        // Amélioration du print pour PDF
        window.addEventListener('beforeprint', function() {
            document.body.style.backgroundColor = 'white';
        });
    </script>
</body>
</html>
