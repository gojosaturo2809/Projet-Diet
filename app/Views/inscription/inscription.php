<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>NutriPlan â€” Design Preview</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="<?= base_url('css/inscription.css') ?>">
</head>
<body>

<!-- BARRE DE NAVIGATION DES PREVIEWS -->
<div class="preview-bar">
    <div class="preview-brand">Nutri<span>Plan</span> â€” Design Preview</div>
    <button class="tab-btn active" onclick="showTab('login')">Login</button>
    <button class="tab-btn" onclick="showTab('register')">Inscription</button>
    <button class="tab-btn" onclick="showTab('dashboard')">Dashboard User</button>
    <button class="tab-btn" onclick="showTab('regimes')">RÃ©gimes</button>
    <button class="tab-btn" onclick="showTab('admin')">Admin Panel</button>
</div>


            <!-- Ã‰tape 1 : Informations personnelles -->
            <div class="card">
                <div class="ch">
                    <span class="ct">CrÃ©er un compte</span>
                    <span class="tmu">Ã‰tape 1 / 3</span>
                </div>

                <form method="POST" action="<?php echo base_url('inscription/inscription'); ?>">
                    <!-- Nom -->
                    <div class="fg">
                        <label class="fl">Nom <span style="color:var(--red-600)">*</span></label>
                        <input type="text" name="nom" class="fc" placeholder="Dupont" value="<?php echo old('nom'); ?>" required>
                        <?php if (isset($errors['nom'])): ?>
                            <small style="color:var(--red-600);"><?php echo $errors['nom']; ?></small>
                        <?php endif; ?>
                    </div>

                    <!-- PrÃ©nom -->
                    <div class="fg">
                        <label class="fl">PrÃ©nom <span style="color:var(--red-600)">*</span></label>
                        <input type="text" name="prenom" class="fc" placeholder="Jean" value="<?php echo old('prenom'); ?>" required>
                        <?php if (isset($errors['prenom'])): ?>
                            <small style="color:var(--red-600);"><?php echo $errors['prenom']; ?></small>
                        <?php endif; ?>
                    </div>

                    <!-- Email -->
                    <div class="fg">
                        <label class="fl">Adresse email <span style="color:var(--red-600)">*</span></label>
                        <input type="email" name="email" class="fc" placeholder="vous@exemple.com" value="<?php echo old('email'); ?>" required>
                        <?php if (isset($errors['email'])): ?>
                            <small style="color:var(--red-600);"><?php echo $errors['email']; ?></small>
                        <?php endif; ?>
                    </div>

                    <!-- Mot de passe -->
                    <div class="fg">
                        <label class="fl">Mot de passe <span style="color:var(--red-600)">*</span></label>
                        <input type="password" name="mot_de_passe" class="fc" placeholder="â€¢â€¢â€¢â€¢â€¢â€¢â€¢â€¢" required>
                        <?php if (isset($errors['mot_de_passe'])): ?>
                            <small style="color:var(--red-600);"><?php echo $errors['mot_de_passe']; ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="fw" style="justify-content:space-between;gap:1rem">
                        <input type="submit" value="S'inscrire" class="btn bp bfull blg">
                    </div>
                </form>

                <p style="text-align:center;font-size:.85rem;color:var(--dark-500);margin-top:1rem">Vous avez dÃ©jÃ  un compte ? <a href="#" style="color:var(--red-600);font-weight:500" onclick="showTab('login');return false">Se connecter</a></p>
            </div>
        </div>
    </div>
</div>
