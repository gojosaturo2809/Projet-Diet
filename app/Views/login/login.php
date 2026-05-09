<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>NutriPlan - Connexion</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600&display=swap" rel="stylesheet">
</head>
<body>
<!-- Messages d'alerte -->
<?php if(session()->getFlashdata('success')): ?>
<div class="alert alert-success">
    <?= session()->getFlashdata('success') ?>
</div>
<?php endif; ?>

<?php if(session()->getFlashdata('error')): ?>
<div class="alert alert-error">
    <?= session()->getFlashdata('error') ?>
</div>
<?php endif; ?>

<?php if(isset($errors)): ?>
<div class="alert alert-error">
    <?php if(is_array($errors)): ?>
        <ul>
            <?php foreach($errors as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <?= esc($errors) ?>
    <?php endif; ?>
</div>
<?php endif; ?>

<div id="tab-login" class="tab-panel active">
    <div class="auth-layout">
        <div class="al">
            <div class="al-inner">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:2.5rem;">
                    <span style="color:rgba(255,255,255,.7);font-size:1.1rem">✦</span>
                    <span style="font-family:var(--font-d);font-size:1.4rem;font-weight:700">NutriPlan</span>
                </div>
                <div class="bt">Votre corps,<br>votre programme.</div>
                <p style="opacity:.85;font-size:.95rem;line-height:1.7;margin-bottom:2.5rem">Découvrez un régime alimentaire personnalisé selon vos objectifs. Calculez votre IMC et transformez votre quotidien.</p>
                <div style="display:flex;gap:2rem">
                    <div><div style="font-family:var(--font-d);font-size:1.7rem;font-weight:700">500+</div><div style="font-size:.78rem;opacity:.75">Utilisateurs</div></div>
                    <div><div style="font-family:var(--font-d);font-size:1.7rem;font-weight:700">5</div><div style="font-size:.78rem;opacity:.75">Régimes</div></div>
                    <div><div style="font-family:var(--font-d);font-size:1.7rem;font-weight:700">15%</div><div style="font-size:.78rem;opacity:.75">Remise Gold</div></div>
                </div>
            </div>
        </div>
        <div class="ar">
            <div class="ab">
                <h2>Bon retour</h2>
                <p>Connectez-vous à votre compte NutriPlan</p>
                <form action="<?= site_url('login') ?>" method="POST">
                    <div class="fg"><label class="fl">Adresse email</label><input type="email" name="email" class="fc" placeholder="vous@exemple.com" required></div>
                    <?php if (isset($errors['email'])): ?>
                        <small style="color:var(--red-600);"><?= esc($errors['email']) ?></small>
                    <?php endif; ?>
                    <div class="fg"><label class="fl">Mot de passe</label><input type="password" name="mot_de_passe" class="fc" placeholder="••••••••" required></div>
                    <?php if (isset($errors['mot_de_passe'])): ?>
                        <small style="color:var(--red-600);"><?= esc($errors['mot_de_passe']) ?></small>
                    <?php endif; ?>
                    <div style="display:flex;justify-content:flex-end;margin:-.5rem 0 1rem"><a href="#" style="font-size:.8rem;color:var(--red-600)">Mot de passe oublié ?</a></div>
                    <input type="submit" value="Se connecter" class="btn bp bfull blg">
                </form>
                <div class="dv"></div>
                <p style="text-align:center;font-size:.85rem;color:var(--dark-500)">Pas encore de compte ? <a href="<?= site_url('inscription') ?>" style="color:var(--red-600);font-weight:500">Créer un compte</a></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>