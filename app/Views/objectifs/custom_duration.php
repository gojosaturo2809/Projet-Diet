<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Durée personnalisée - NutriPlan</title>
    <link rel="stylesheet" href="<?= base_url('css/objectifs.css') ?>">
</head>
<body>
    <div class="objectifs-shell">
        <main>
            <div class="objectifs-card">
                <div class="objectifs-header">
                    <div class="step-badge"><span>3</span> Étape 3 / 3</div>
                    <h2>Choisissez une durée personnalisée</h2>
                    <p>Indiquez la durée en mois ou en semaines pour votre programme.</p>
                </div>

                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="msg err"><?= esc(session()->getFlashdata('error')) ?></div>
                <?php endif; ?>

                <form action="<?= site_url('objectifs/selectionner') ?>" method="post">
                    <?= csrf_field() ?>

                    <input type="hidden" name="id_objectif" value="<?= esc($idObjectif ?? '') ?>">
                    <input type="hidden" name="poids_cible" value="<?= esc($poidsCible ?? '') ?>">

                    <div style="margin-bottom:12px;">
                        <label style="display:block;font-weight:bold;margin-bottom:6px;">Durée (en mois)</label>
                        <input type="number" name="duree_mois" id="duree_mois" min="1" step="1" placeholder="Ex: 4" style="padding:10px;width:100%;border-radius:8px;border:1px solid #e8e6e1;">
                        <small style="color:#666;">Ou utilisez le champ semaines pour une valeur précise.</small>
                    </div>

                    <div style="margin-bottom:12px;">
                        <label style="display:block;font-weight:bold;margin-bottom:6px;">Durée (en semaines)</label>
                        <input type="number" name="duree_objectif_semaine" id="duree_semaines" min="1" step="1" placeholder="Ex: 16" style="padding:10px;width:100%;border-radius:8px;border:1px solid #e8e6e1;">
                    </div>

                    <div style="display:flex;gap:10px;align-items:center;">
                        <button type="submit" class="btn primary">Valider la durée</button>
                        <a href="<?= site_url('objectifs') ?>" class="btn">Annuler</a>
                    </div>
                </form>

            </div>
        </main>
    </div>

<script>
    // Lors de la soumission, si le champ mois est renseigné et semaines vide,
    // convertir mois -> semaines (1 mois = 4 semaines approximation)
    (function(){
        const form = document.querySelector('form');
        if (!form) return;
        form.addEventListener('submit', function(e){
            const mois = parseInt(document.getElementById('duree_mois').value || 0, 10);
            const semaines = parseInt(document.getElementById('duree_semaines').value || 0, 10);
            if (!semaines && mois) {
                // convertir
                const calcSemaines = Math.max(1, mois * 4);
                // injecter dans le champ attendu par la validation
                document.getElementById('duree_semaines').value = calcSemaines;
            }
            // Si aucun renseigné, laisser la soumission pour que la validation serveur gère
        });
    })();
</script>
</body>
</html>
