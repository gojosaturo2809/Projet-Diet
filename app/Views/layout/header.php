<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= esc($title ?? 'NutriPlan') ?></title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url('css/wallet-gold.css') ?>">
</head>
<body>
<nav class="navbar">
    <div class="nb-brand">Nutri<span class="accent">Plan</span></div>
    <ul class="nb-nav">
        <li><a href="<?= base_url('wallet') ?>" class="<?= ($active ?? '') === 'wallet' ? 'active' : '' ?>">Portefeuille</a></li>
        <li><a href="<?= base_url('wallet/recharge') ?>" class="<?= ($active ?? '') === 'recharge' ? 'active' : '' ?>">Recharger</a></li>
        <li><a href="<?= base_url('gold') ?>" class="<?= ($active ?? '') === 'gold' ? 'active' : '' ?>">Option Gold</a></li>
    </ul>
</nav>
<main class="container">
