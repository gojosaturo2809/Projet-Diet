<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriPlan - Choisir mon objectif</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/objectifs.css') ?>">
</head>
<body>
    <div class="objectifs-shell">
        <main>
            <div class="objectifs-card">
                <div class="objectifs-header">
                    <div class="step-badge"><span>3</span> Étape 3 / 3</div>
                    <h2>Choisissez votre objectif</h2>
                    <p>Définissez votre but pour que nous puissions adapter votre programme.</p>
                </div>

                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="msg err"><?= esc(session()->getFlashdata('error')) ?></div>
                <?php endif; ?>

                <form action="<?= site_url('objectifs/selectionner') ?>" method="post" id="form-objectif">
                    <?= csrf_field() ?>

                    <?php if (!empty($objectifs)) : ?>
                        <div class="objectifs-grid">
                            <?php foreach ($objectifs as $obj) : ?>
                                <label class="objectif-card" for="obj_<?= $obj['id'] ?>" onclick="loadDetails(<?= $obj['id'] ?>)">
                                    <input type="radio" name="id_objectif" value="<?= $obj['id'] ?>" id="obj_<?= $obj['id'] ?>" 
                                           <?= old('id_objectif') == $obj['id'] ? 'checked' : '' ?> required>
                                    <h3><?= esc($obj['nom']) ?></h3>
                                </label>
                            <?php endforeach; ?>
                        </div>

                        <div id="details-container">
                            <div id="ajax-content"></div>
                            
                            <?php if (session()->getFlashdata('error_poids')) : ?>
                                <p class="error-text"><?= session()->getFlashdata('error_poids') ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="objectifs-actions">
                            <small class="actions-info">L'algorithme calculera votre régime après validation.</small>
                            <button class="btn primary" type="submit" id="btn-submit" style="display:none;">Confirmer mon choix</button>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </main>
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