<?php
/**
 * Vue partielle chargée via AJAX
 * Variables disponibles : $idObjectif, $objectif, $poidsIdeal, $poidsActuel, $taille, $error
 */

$objectif ??= [];
$poidsIdeal ??= 0;
$poidsActuel ??= 0;
$taille ??= 0;
$error ??= null;
?>

<div class="form-group-details" style="animation: fadeIn 0.4s ease;">
    <h4 style="margin-top: 0; color: #c0392b;">Configuration de votre but</h4>

    <?php if (! empty($error)) : ?>
        <div class="error-text" style="margin-bottom: 12px;"><?= esc($error) ?></div>
        <?php return; ?>
    <?php endif; ?>
    
    <?php if (($objectif['nom'] ?? '') === 'Maintien du poids') : ?>
        <p style="background: #ebf5fb; padding: 12px; border-radius: 8px; border-left: 4px solid #2e86c1;">
            D'après votre taille, votre poids de forme idéal est estimé à <strong><?= esc($poidsIdeal) ?> kg</strong>.
        </p>
        <input type="hidden" name="poids_cible" value="<?= esc($poidsIdeal) ?>">
    <?php else : ?>
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: bold;">
                <?= ($objectif['nom'] ?? '') === 'Prise de muscle' ? 'Quel poids cible visez-vous ?' : 'Quel est le poids que vous visez ?' ?>
            </label>
            <input type="number" 
                   name="poids_cible" 
                   step="0.1" 
                   class="form-control <?= session()->getFlashdata('error_poids') ? 'input-error' : '' ?>"
                   placeholder="Ex: <?= ($objectif['nom'] ?? '') === 'Prise de muscle' ? esc($poidsActuel + 5) : esc(max(0, $poidsActuel - 5)) ?> kg"
                   value="<?= esc(old('poids_cible')) ?>"
                   style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #e8e6e1;"
                   required>
        </div>
    <?php endif; ?>

    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px; font-weight: bold;">En combien de temps ? (Durée)</label>
        <select name="duree_objectif_semaine" id="select-duree" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #e8e6e1;" required>
            <option value="" disabled <?= ! old('duree_objectif_semaine') ? 'selected' : '' ?>>Choisir la durée...</option>
            <option value="4" <?= old('duree_objectif_semaine') == 4 ? 'selected' : '' ?>>1 mois (Rapide)</option>
            <option value="8" <?= old('duree_objectif_semaine') == 8 ? 'selected' : '' ?>>2 mois (Équilibré)</option>
            <option value="12" <?= old('duree_objectif_semaine') == 12 ? 'selected' : '' ?>>3 mois (Recommandé)</option>
            <option value="custom" <?= old('duree_objectif_semaine') == 'custom' ? 'selected' : '' ?>>4 mois ou plus (Personnalisé)</option>
        </select>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .form-control:focus { outline: none; border-color: #c0392b; box-shadow: 0 0 0 2px rgba(192,57,43,0.1); }
</style>
<script>
    (function(){
        const select = document.getElementById('select-duree');
        if (!select) return;

        select.addEventListener('change', function(e){
            const btn = document.getElementById('btn-submit');
            if (this.value === 'custom') {
                // Désactiver le bouton de soumission principal pour éviter d'envoyer 'custom' au serveur
                if (btn) btn.disabled = true;

                // Récupérer id_objectif sélectionné et poids_cible si présent
                const form = document.getElementById('form-objectif');
                const idObjRadio = document.querySelector('input[name="id_objectif"]:checked');
                const poidsInput = form ? form.querySelector('input[name="poids_cible"]') : null;
                const idObj = idObjRadio ? idObjRadio.value : '';
                const poids = poidsInput ? poidsInput.value : '';

                // Construire l'URL de redirection vers la page personnalisée
                const url = new URL('<?= site_url('objectifs/custom') ?>', window.location.origin);
                if (idObj) url.searchParams.set('id_objectif', idObj);
                if (poids) url.searchParams.set('poids_cible', poids);

                window.location.href = url.toString();
                return;
            }

            // Réactiver le bouton si une durée valide est choisie
            if (btn) btn.disabled = false;
        });
    })();
</script>