<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriPlan - Inscription</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/inscription.css') ?>">
</head>
<body>
<?php
$healthData = (isset($healthData) && is_array($healthData)) ? $healthData : [];
$errors = (isset($errors) && is_array($errors)) ? $errors : [];
$initialStep = ! empty($healthData) ? 2 : 1;
$imcValue = isset($healthData['imc']) ? number_format((float) $healthData['imc'], 1, ',', ' ') : '—';
$imcLabel = $healthData['categorie'] ?? 'Renseignez votre poids et votre taille pour calculer votre IMC.';
$flashError = session()->getFlashdata('error');
$flashSuccess = session()->getFlashdata('success');
?>
<main class="signup-shell">
    <section class="signup-card">
        <div class="brand-row">
            <div class="brand">NutriPlan</div>
        </div>

        <div class="wizard-steps" data-wizard-steps>
            <div class="wizard-step <?= $initialStep >= 1 ? 'is-active' : '' ?>" data-step-indicator="1">
                <span>1</span>
                <strong>Santé</strong>
            </div>
            <div class="wizard-line"></div>
            <div class="wizard-step <?= $initialStep >= 2 ? 'is-active' : '' ?>" data-step-indicator="2">
                <span>2</span>
                <strong>Compte</strong>
            </div>
            <div class="wizard-line"></div>
            <div class="wizard-step" data-step-indicator="3">
                <span>3</span>
                <strong>Objectif</strong>
            </div>
        </div>

        <?php if ($flashError) : ?>
            <div class="flash flash-error"><?= esc((string) $flashError) ?></div>
        <?php endif; ?>

        <?php if ($flashSuccess) : ?>
            <div class="flash flash-success"><?= esc((string) $flashSuccess) ?></div>
        <?php endif; ?>

        <div class="wizard-panels">
            <section class="wizard-panel <?= $initialStep === 1 ? 'is-visible' : '' ?>" data-panel="health">
                <div class="panel-head center">
                    <div>
                        <p class="eyebrow">Étape 1 / 3</p>
                        <h2>Informations de santé</h2>
                    </div>
                </div>

                <form class="step-form" action="<?= site_url('inscription/sante') ?>" method="post" data-health-form>
                    <div class="grid-two">
                        <div class="field">
                            <label for="poids">Poids (kg)</label>
                            <input id="poids" name="poids" type="number" min="1" step="0.1" placeholder="65" value="<?= esc($healthData['poids'] ?? '') ?>" required>
                            <small class="field-error" data-error-for="poids"></small>
                        </div>
                        <div class="field">
                            <label for="taille">Taille (cm)</label>
                            <input id="taille" name="taille" type="number" min="50" step="0.1" placeholder="170" value="<?= esc($healthData['taille'] ?? '') ?>" required>
                            <small class="field-error" data-error-for="taille"></small>
                        </div>
                    </div>

                    <div class="imc-card" data-imc-card>
                        <p class="imc-label">IMC calculé</p>
                        <div class="imc-value" data-imc-value><?= esc($imcValue) ?></div>
                        <p class="imc-caption" data-imc-caption><?= esc($imcLabel) ?></p>
                    </div>

                    <div class="step-actions">
                        <button type="submit" class="btn btn-primary">Continuer</button>
                    </div>
                    <div class="flash flash-error is-hidden" data-health-message></div>
                </form>
            </section>

            <section class="wizard-panel <?= $initialStep === 2 ? 'is-visible' : '' ?>" data-panel="account">
                <div class="panel-head center">
                    <div>
                        <p class="eyebrow">Étape 2 / 3</p>
                        <h2>Informations personnelles</h2>
                    </div>
                </div>

                <?php if (! empty($errors)) : ?>
                    <div class="flash flash-error">
                        <ul class="error-list">
                            <?php foreach ($errors as $error) : ?>
                                <li><?= esc((string) $error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="health-summary">
                    <span>Vos données santé sont enregistrées.</span>
                    <strong><?= esc($imcLabel) ?> - IMC <?= esc($imcValue) ?></strong>
                </div>

                <form class="step-form" action="<?= site_url('inscription/inscription') ?>" method="post" data-account-form>
                    <div class="grid-two">
                        <div class="field">
                            <label for="nom">Nom</label>
                            <input id="nom" name="nom" type="text" placeholder="Dupont" value="<?= esc(old('nom')) ?>" required>
                            <?php if (isset($errors['nom'])) : ?><small class="field-error visible"><?= esc($errors['nom']) ?></small><?php endif; ?>
                        </div>
                        <div class="field">
                            <label for="prenom">Prénom</label>
                            <input id="prenom" name="prenom" type="text" placeholder="Jean" value="<?= esc(old('prenom')) ?>" required>
                            <?php if (isset($errors['prenom'])) : ?><small class="field-error visible"><?= esc($errors['prenom']) ?></small><?php endif; ?>
                        </div>
                    </div>

                    <div class="field">
                        <fieldset class="gender-fieldset">
                            <legend class="genre-label">Genre</legend>
                            <div class="radio-group">
                                <label class="radio-label">
                                    <input type="radio" name="genre" value="homme" <?= (old('genre') === 'homme') ? 'checked' : '' ?> required>
                                    <span class="radio-custom"></span>
                                    <span class="radio-text">Homme</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="genre" value="femme" <?= (old('genre') === 'femme') ? 'checked' : '' ?> required>
                                    <span class="radio-custom"></span>
                                    <span class="radio-text">Femme</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="genre" value="autre" <?= (old('genre') === 'autre') ? 'checked' : '' ?> required>
                                    <span class="radio-custom"></span>
                                    <span class="radio-text">Autre</span>
                                </label>
                            </div>
                            <?php if (isset($errors['genre'])) : ?><small class="field-error visible"><?= esc($errors['genre']) ?></small><?php endif; ?>
                        </fieldset>
                    </div>

                    <div class="field">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" placeholder="vous@exemple.com" value="<?= esc(old('email')) ?>" required>
                        <?php if (isset($errors['email'])) : ?><small class="field-error visible"><?= esc($errors['email']) ?></small><?php endif; ?>
                    </div>

                    <div class="field">
                        <label for="mot_de_passe">Mot de passe</label>
                        <input id="mot_de_passe" name="mot_de_passe" type="password" placeholder="••••••••" required>
                        <?php if (isset($errors['mot_de_passe'])) : ?><small class="field-error visible"><?= esc($errors['mot_de_passe']) ?></small><?php endif; ?>
                    </div>

                    <div class="step-actions two-actions">
                        <button type="button" class="btn btn-secondary" data-back-to-health>Retour</button>
                        <button type="submit" class="btn btn-primary">Créer mon compte</button>
                    </div>
                </form>

                <p class="login-link">Vous avez déjà un compte ? <a href="<?= site_url('login') ?>">Se connecter</a></p>
            </section>
        </div>
    </section>
</main>

<script>
window.__inscriptionState = {
    initialStep: <?= (int) $initialStep ?>,
    imcValue: <?= json_encode($healthData['imc'] ?? null) ?>,
    imcLabel: <?= json_encode($healthData['categorie'] ?? null) ?>
};
</script>
<script src="<?= base_url('js/inscription.js') ?>"></script>
</body>
</html>