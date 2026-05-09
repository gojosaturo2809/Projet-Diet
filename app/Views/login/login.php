<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>NutriPlan â€” Design Preview</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="<?= base_url('css/login.css') ?>">
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

<!-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• LOGIN â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• -->
<div id="tab-login" class="tab-panel active">
    <div class="auth-layout">
        <div class="al">
            <div class="al-inner">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:2.5rem;">
                    <span style="color:rgba(255,255,255,.7);font-size:1.1rem">âœ¦</span>
                    <span style="font-family:var(--font-d);font-size:1.4rem;font-weight:700">NutriPlan</span>
                </div>
                <div class="bt">Votre corps,<br>votre programme.</div>
                <p style="opacity:.85;font-size:.95rem;line-height:1.7;margin-bottom:2.5rem">DÃ©couvrez un rÃ©gime alimentaire personnalisÃ© selon vos objectifs. Calculez votre IMC et transformez votre quotidien.</p>
                <div style="display:flex;gap:2rem">
                    <div><div style="font-family:var(--font-d);font-size:1.7rem;font-weight:700">500+</div><div style="font-size:.78rem;opacity:.75">Utilisateurs</div></div>
                    <div><div style="font-family:var(--font-d);font-size:1.7rem;font-weight:700">5</div><div style="font-size:.78rem;opacity:.75">RÃ©gimes</div></div>
                    <div><div style="font-family:var(--font-d);font-size:1.7rem;font-weight:700">15%</div><div style="font-size:.78rem;opacity:.75">Remise Gold</div></div>
                </div>
            </div>
        </div>
        <div class="ar">
            <div class="ab">
                <h2>Bon retour </h2>
                <p>Connectez-vous Ã  votre compte NutriPlan</p>
                <div class="alert adang">Email ou mot de passe incorrect.</div>
                <div class="fg"><label class="fl">Adresse email</label><input type="email" class="fc" placeholder="vous@exemple.com"></div>
                <div class="fg"><label class="fl">Mot de passe</label><input type="password" class="fc" placeholder="â€¢â€¢â€¢â€¢â€¢â€¢â€¢â€¢"></div>
                <div style="display:flex;justify-content:flex-end;margin:-.5rem 0 1rem"><a href="#" style="font-size:.8rem;color:var(--red-600)">Mot de passe oubliÃ© ?</a></div>
                <button class="btn bp bfull blg">Se connecter</button>
                <div class="dv"></div>
                <p style="text-align:center;font-size:.85rem;color:var(--dark-500)">Pas encore de compte ? <a href="<?php echo base_url('inscription'); ?>" style="color:var(--red-600);font-weight:500">CrÃ©er un compte</a></p>
            </div>
        </div>
    </div>
</div>
