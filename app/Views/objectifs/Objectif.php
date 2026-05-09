<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriPlan - Choisir mon objectif</title>
    <style>
        body{font-family:Arial,sans-serif;background:#f5f5f3;margin:0;color:#1a1917}
        .wrap{max-width:960px;margin:0 auto;padding:32px 20px}
        .hero{background:#c0392b;color:#fff;border-radius:24px;padding:28px;margin-bottom:20px}
        .hero h1{margin:0 0 8px;font-size:2rem}
        .hero p{margin:0;opacity:.9}
        .msg{padding:12px 14px;border-radius:12px;margin-bottom:16px}
        .msg.err{background:#fef0ef;color:#922b21;border-left:4px solid #c0392b}
        .msg.ok{background:#eafaf1;color:#1e8449;border-left:4px solid #2ecc71}
        .grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
        .card{background:#fff;border:1px solid #e8e6e1;border-radius:18px;padding:18px;cursor:pointer;transition:.2s ease}
        .card:hover{border-color:#c0392b;background:#fef0ef}
        .card input{margin-bottom:10px}
        .card h3{margin:0 0 6px;font-size:1rem}
        .card p{margin:0;color:#6b6860;font-size:.9rem}
        .actions{display:flex;justify-content:space-between;align-items:center;margin-top:18px;gap:12px}
        .btn{border:none;border-radius:12px;padding:12px 18px;cursor:pointer;font-weight:700}
        .btn.primary{background:#c0392b;color:#fff}
        .btn.primary:hover{background:#922b21}
        .current{background:#ebf5fb;border-left:4px solid #2e86c1;border-radius:12px;padding:14px 16px;margin-bottom:16px}
        .current strong{display:block;margin-bottom:4px}
        .step-badge{display:inline-flex;align-items:center;gap:8px;background:#fff;color:#c0392b;border-radius:999px;padding:8px 14px;font-weight:700;margin-bottom:14px}
        .step-badge span{background:#c0392b;color:#fff;width:28px;height:28px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center}
        @media (max-width: 840px){.grid{grid-template-columns:1fr}.actions{flex-direction:column;align-items:stretch}}
    </style>
</head>
<body>
    <div class="wrap">
        <div class="hero">
            <div class="step-badge"><span>3</span> Étape 3 / 3</div>
            <h1>Choisissez votre objectif</h1>
            <p>Cette étape termine l'inscription. Le choix est enregistré en base et dans la session.</p>
        </div>

        <?php if (session()->getFlashdata('success')) : ?>
            <div class="msg ok"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="msg err"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <?php if (! empty($objectifActuel)) : ?>
            <div class="current">
                <strong>Objectif actuel</strong>
                <?= esc($objectifActuel['nom']) ?>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('objectifs/selectionner') ?>" method="post">
            <?= csrf_field() ?>

            <?php if (!empty($objectifs) && is_array($objectifs)) : ?>
                <div class="grid">
                    <?php foreach ($objectifs as $obj) : ?>
                        <label class="card" for="obj_<?= esc($obj['id']) ?>">
                            <input type="radio" name="id_objectif" value="<?= esc($obj['id']) ?>" id="obj_<?= esc($obj['id']) ?>" required>
                            <h3><?= esc($obj['nom']) ?></h3>
                            <p>
                                <?php if ($obj['nom'] === 'Perte de poids') : ?>
                                    Réduire progressivement votre poids.
                                <?php elseif ($obj['nom'] === 'Prise de muscle') : ?>
                                    Augmenter votre masse musculaire.
                                <?php else : ?>
                                    Maintenir votre poids actuel.
                                <?php endif; ?>
                            </p>
                        </label>
                    <?php endforeach; ?>
                </div>

                <div class="actions">
                    <small style="color:#6b6860">Choisissez un seul objectif pour continuer.</small>
                    <button class="btn primary" type="submit">Confirmer mon choix</button>
                </div>
            <?php else : ?>
                <p>Aucun objectif disponible pour le moment.</p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
