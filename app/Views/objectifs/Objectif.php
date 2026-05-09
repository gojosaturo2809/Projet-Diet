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

        #details-container { background: #fff; border-radius: 18px; padding: 25px; margin-top: 20px; border: 1px solid #e8e6e1; display: none; }
        
        .actions{display:flex;justify-content:space-between;align-items:center;margin-top:18px;gap:12px}
        .btn{border:none;border-radius:12px;padding:12px 18px;cursor:pointer;font-weight:700}
        .btn.primary{background:#c0392b;color:#fff}
        
        .step-badge{display:inline-flex;align-items:center;gap:8px;background:#fff;color:#c0392b;border-radius:999px;padding:8px 14px;font-weight:700;margin-bottom:14px}
        .step-badge span{background:#c0392b;color:#fff;width:28px;height:28px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center}
        @media (max-width: 840px){.grid{grid-template-columns:1fr}}
    </style>
</head>
<body>
    <div class="wrap">
        <div class="hero">
            <div class="step-badge"><span>3</span> Étape 3 / 3</div>
            <h1>Choisissez votre objectif</h1>
            <p>Définissez votre but pour que nous puissions adapter votre programme.</p>
        </div>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="msg err"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <form action="<?= site_url('objectifs/selectionner') ?>" method="post" id="form-objectif">
            <?= csrf_field() ?>

            <?php if (!empty($objectifs)) : ?>
                <div class="grid">
                    <?php foreach ($objectifs as $obj) : ?>
                        <label class="card" for="obj_<?= $obj['id'] ?>" onclick="loadDetails(<?= $obj['id'] ?>)">
                            <input type="radio" name="id_objectif" value="<?= $obj['id'] ?>" id="obj_<?= $obj['id'] ?>" 
                                   <?= old('id_objectif') == $obj['id'] ? 'checked' : '' ?> required>
                            <h3><?= esc($obj['nom']) ?></h3>
                        </label>
                    <?php endforeach; ?>
                </div>

                <div id="details-container">
                    <div id="ajax-content">
                        </div>
                    
                    <?php if (session()->getFlashdata('error_poids')) : ?>
                        <p class="error-text"><?= session()->getFlashdata('error_poids') ?></p>
                    <?php endif; ?>
                </div>

                <div class="actions">
                    <small style="color:#6b6860">L'algorithme calculera votre régime après validation.</small>
                    <button class="btn primary" type="submit" id="btn-submit" style="display:none;">Confirmer mon choix</button>
                </div>
            <?php endif; ?>
        </form>
    </div>

    <script>
        function loadDetails(idObjectif) {
            const container = document.getElementById('details-container');
            const content = document.getElementById('ajax-content');
            const btn = document.getElementById('btn-submit');

            // Animation visuelle
            container.style.display = 'block';
            content.innerHTML = '<p>Chargement des paramètres...</p>';

            // Appel AJAX vers le contrôleur
            fetch('<?= site_url('objectifs/getDetailsForm') ?>?id_objectif=' + idObjectif)
                .then(response => response.text())
                .then(html => {
                    content.innerHTML = html;
                    btn.style.display = 'block';
                    
                    // Si on revient d'une erreur de validation, on applique la classe rouge
                    <?php if (session()->getFlashdata('error_poids')) : ?>
                        const inputPoids = document.querySelector('input[name="poids_cible"]');
                        if(inputPoids) inputPoids.classList.add('input-error');
                    <?php endif; ?>
                })
                .catch(error => {
                    content.innerHTML = '<p class="error-text">Erreur de chargement.</p>';
                });
        }

        // Si la page est rechargée avec une erreur, on réaffiche les détails
        <?php if (old('id_objectif')) : ?>
            window.onload = () => loadDetails(<?= old('id_objectif') ?>);
        <?php endif; ?>
    </script>
</body>
</html>