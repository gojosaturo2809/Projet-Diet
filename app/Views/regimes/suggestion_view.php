<?= view('layout/header', ['title' => 'Suggestion de régime', 'active' => 'regimes']) ?>

<div class="container" style="max-width:1100px;margin:30px auto;padding:20px;">
    <div class="card" style="background:#fff;border-radius:18px;padding:24px;box-shadow:0 10px 30px rgba(0,0,0,.06);">
        <h1 style="margin:0 0 10px;font-family:var(--font-d);">Régime suggéré</h1>
        <p style="color:#666;margin-bottom:20px;">Recommandation basée sur votre objectif, votre IMC et votre niveau d'activité.</p>

        <?php if (! empty($regime)) : ?>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px;align-items:start;">
                <div style="border:1px solid #eee;border-radius:14px;padding:18px;">
                    <h2 style="margin:0 0 8px;font-size:24px;"><?= esc((string) ($regime['nom'] ?? '—')) ?></h2>
                    <p style="margin:0 0 12px;color:#666;">
                        <?= esc((string) ($regime['description'] ?? '')) ?>
                    </p>
                    <p style="margin:0;color:#444;">
                        Prix total: <strong><?= number_format((float) ($prixTotal ?? 0), 0, ',', ' ') ?> Ar</strong>
                    </p>
                    <?php if (! empty($remise)) : ?>
                        <p style="margin:6px 0 0;color:#b45309;">Remise Gold: <?= number_format((float) $remise, 0, ',', ' ') ?> Ar</p>
                    <?php endif; ?>
                </div>

                <div style="border:1px solid #eee;border-radius:14px;padding:18px;">
                    <h3 style="margin:0 0 12px;font-size:18px;">Sport conseillé</h3>
                    <?php if (! empty($sport)) : ?>
                        <p style="margin:0 0 6px;"><strong><?= esc((string) ($sport['nom'] ?? '—')) ?></strong></p>
                        <p style="margin:0;color:#666;">Intensité: <?= esc((string) ($sport['intensite'] ?? '—')) ?></p>
                        <?php if (! empty($sport['calories_heure'])) : ?>
                            <p style="margin:6px 0 0;color:#666;"><?= (int) $sport['calories_heure'] ?> cal/h</p>
                        <?php endif; ?>
                    <?php else : ?>
                        <p style="margin:0;color:#666;">Aucun sport trouvé.</p>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (! empty($regime['variation_poids_hebdo']) || ! empty($deltaPoids)) : ?>
                <div style="margin-top:18px;color:#444;">Variation hebdomadaire cible: <?= number_format((float) ($regime['variation_poids_hebdo'] ?? 0), 2, ',', ' ') ?> kg</div>
                <div style="color:#444;">Écart poids objectif: <?= number_format((float) ($deltaPoids ?? 0), 2, ',', ' ') ?> kg</div>
            <?php endif; ?>

            <?php if (! empty($regime['activites_combinations']) && is_array($regime['activites_combinations'])) : ?>
                <div style="margin-top:24px;">
                    <h3 style="margin:0 0 12px;font-size:18px;">Combinaisons d'activités</h3>
                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:12px;">
                        <?php foreach ($regime['activites_combinations'] as $combo) : ?>
                            <div style="border:1px solid #eee;border-radius:12px;padding:14px;">
                                <strong><?= esc((string) ($combo['label'] ?? '—')) ?></strong>
                                <div style="color:#666;margin-top:6px;"><?= (int) ($combo['calories_total'] ?? 0) ?> cal/h cumulées</div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div style="margin-top:24px;display:flex;gap:12px;flex-wrap:wrap;">
                <form method="post" action="<?= base_url('regimes/souscrire') ?>">
                    <input type="hidden" name="regime_id" value="<?= (int) ($regime['id'] ?? 0) ?>">
                    <input type="hidden" name="semaines" value="<?= (int) ($objectif['duree_objectif_semaine'] ?? 4) ?>">
                    <button class="btn" type="submit" style="background:#c63b2d;color:#fff;">Souscrire maintenant</button>
                </form>
                <a href="<?= site_url('regimes') ?>" class="btn" style="background:#f5f5f5;color:#333;">Retour</a>
            </div>
        <?php else : ?>
            <p>Aucune suggestion disponible.</p>
        <?php endif; ?>
    </div>
</div>

<?= view('layout/footer') ?>