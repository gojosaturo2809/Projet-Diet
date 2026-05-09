<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriPlan — Choix de l'objectif</title>
    <style>
        :root {
            --red-50:#FEF0EF;--red-200:#F9A49E;--red-600:#C0392B;--red-800:#922B21;
            --dark-100:#F5F5F3;--dark-200:#E8E6E1;--dark-500:#6B6860;--dark-900:#1A1917;
            --white:#FFFFFF;--gold:#D4A017;--gold-light:#FAF0C8;
            --font-d:'Playfair Display',serif;--font-b:'DM Sans',sans-serif;
            --r-md:12px;--r-lg:20px;--sh-sm:0 1px 3px rgba(0,0,0,.08);--sh-md:0 4px 16px rgba(0,0,0,.10);
        }
        *{box-sizing:border-box} body{margin:0;font-family:var(--font-b);background:linear-gradient(180deg,var(--dark-100),#fff);color:var(--dark-900)}
        a{text-decoration:none;color:inherit}
        .wrap{max-width:980px;margin:0 auto;padding:32px 20px 40px}
        .top{display:flex;justify-content:space-between;align-items:center;margin-bottom:24px}
        .brand{font-family:var(--font-d);font-size:1.6rem;font-weight:700}
        .brand span,.accent{color:var(--red-600)}
        .badge{display:inline-flex;align-items:center;padding:4px 12px;border-radius:999px;background:var(--gold-light);color:var(--gold);font-size:.78rem;font-weight:700;letter-spacing:.04em}
        .hero{background:linear-gradient(135deg,var(--red-600),var(--red-800));color:#fff;border-radius:28px;padding:28px;box-shadow:var(--sh-md);margin-bottom:20px}
        .hero h1{font-family:var(--font-d);font-size:2rem;line-height:1.1;margin:0 0 8px}
        .hero p{margin:0;opacity:.9;max-width:720px}
        .alert{padding:12px 14px;border-radius:12px;margin-bottom:16px;border-left:4px solid}
        .succ{background:#EAFAF1;border-color:#2ECC71;color:#1E8449}.err{background:var(--red-50);border-color:var(--red-600);color:var(--red-800)}
        .card{background:#fff;border:1px solid var(--dark-200);border-radius:var(--r-lg);box-shadow:var(--sh-sm);padding:22px}
        .card-head{display:flex;justify-content:space-between;gap:12px;align-items:flex-start;margin-bottom:18px}
        .card-head h2{font-family:var(--font-d);font-size:1.3rem;margin:0}
        .grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
        .obj{border:2px solid var(--dark-200);border-radius:18px;padding:18px;cursor:pointer;transition:.2s ease;background:#fff}
        .obj:hover,.obj.selected{border-color:var(--red-600);background:var(--red-50)}
        .obj input{margin-bottom:12px}
        .obj .icon{font-size:2rem;margin-bottom:10px}
        .obj h3{margin:0 0 6px;font-size:1rem}
        .obj p{margin:0;color:var(--dark-500);font-size:.9rem;line-height:1.45}
        .footer{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-top:18px}
        .btn{display:inline-flex;align-items:center;justify-content:center;border:none;border-radius:12px;padding:12px 18px;font-weight:600;cursor:pointer}
        .btn-primary{background:var(--red-600);color:#fff}.btn-primary:hover{background:var(--red-800)}
        .btn-light{background:var(--dark-100);color:var(--dark-900)}
        .current{margin-bottom:18px;padding:14px 16px;border-radius:14px;background:#EBF5FB;border:1px solid #B5D9EE;color:#1A5276}
        .current strong{display:block;margin-bottom:4px}
        .empty{padding:18px;border-radius:14px;background:var(--dark-100);color:var(--dark-500);text-align:center}
        @media (max-width: 860px){.grid{grid-template-columns:1fr}.card-head,.footer,.top{flex-direction:column;align-items:flex-start}}
    </style>
</head>
<body>
    <div class="wrap">
        <div class="top">
            <div class="brand">Nutri<span>Plan</span></div>
            <span class="badge">Étape objectif</span>
        </div>

        <div class="hero">
            <h1>Choisissez votre objectif santé</h1>
            <p>Cette étape termine l'inscription. Une fois l'objectif choisi, il sera enregistré dans la session et lié à votre compte.</p>
        </div>

        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert succ"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert err"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <?php if (! empty($objectifActuel)) : ?>
            <div class="current">
                <strong>Objectif actuel</strong>
                <?= esc($objectifActuel['nom']) ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-head">
                <div>
                    <h2>Liste des objectifs disponibles</h2>
                    <p style="margin:6px 0 0;color:var(--dark-500)">Sélectionnez une seule option pour continuer.</p>
                </div>
                <a class="btn btn-light" href="<?= site_url('inscription') ?>">Retour inscription</a>
            </div>

            <?php if (! empty($objectifs) && is_array($objectifs)) : ?>
                <form action="<?= site_url('objectifs/selectionner') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="grid">
                        <?php foreach ($objectifs as $objectif) : ?>
                            <?php $checked = ! empty($objectifActuel) && (int) $objectifActuel['id_objectif'] === (int) $objectif['id']; ?>
                            <label class="obj <?= $checked ? 'selected' : '' ?>" for="objectif_<?= esc($objectif['id']) ?>">
                                <input type="radio" name="id_objectif" id="objectif_<?= esc($objectif['id']) ?>" value="<?= esc($objectif['id']) ?>" <?= $checked ? 'checked' : '' ?> required>
                                <div class="icon">✦</div>
                                <h3><?= esc($objectif['nom']) ?></h3>
                                <p>
                                    <?php if ($objectif['nom'] === 'Augmenter son poids') : ?>
                                        Programme orienté prise de masse et progression.
                                    <?php elseif ($objectif['nom'] === 'Réduire son poids') : ?>
                                        Programme orienté perte de poids progressive.
                                    <?php else : ?>
                                        Programme orienté équilibre et IMC idéal.
                                    <?php endif; ?>
                                </p>
                            </label>
                        <?php endforeach; ?>
                    </div>

                    <div class="footer">
                        <small style="color:var(--dark-500)">Le choix sera sauvegardé en base et en session.</small>
                        <button class="btn btn-primary" type="submit">Confirmer mon objectif</button>
                    </div>
                </form>
            <?php else : ?>
                <div class="empty">Aucun objectif disponible pour le moment.</div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>