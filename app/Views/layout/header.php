<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= esc($title ?? 'NutriPlan') ?></title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url('css/wallet-gold.css') ?>">
<?php foreach (($extraStylesheets ?? []) as $stylesheet): ?>
<link rel="stylesheet" href="<?= base_url($stylesheet) ?>">
<?php endforeach; ?>
</head>
<body>
<nav class="navbar">
    <div class="nb-brand">Nutri<span class="accent">Plan</span></div>
    <ul class="nb-nav">
        <?php
        $defaultLinks = [
            ['key' => 'dashboard', 'label' => 'Dashboard', 'href' => base_url('dashboard')],
            ['key' => 'wallet', 'label' => 'Portefeuille', 'href' => base_url('wallet')],
            ['key' => 'recharge', 'label' => 'Recharger', 'href' => base_url('wallet/recharge')],
            ['key' => 'gold', 'label' => 'Option Gold', 'href' => base_url('gold')],
        ];
        $links = $navLinks ?? $defaultLinks;
        ?>
        <?php foreach ($links as $link): ?>
            <li class="nl <?= ($active ?? '') === ($link['key'] ?? '') ? 'a' : '' ?>">
                <a href="<?= esc($link['href']) ?>"><?= esc($link['label']) ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
    <div class="nb-right">
        <?php if (isset($solde)): ?>
            <div class="wb"><span class="bi">◈</span><?= number_format((float) $solde, 0, ',', ' ') ?> Ar</div>
        <?php endif; ?>
        <?php if (! empty($isGold)): ?>
            <div class="gb">GOLD</div>
        <?php else: ?>
            <a class="gb" href="<?= base_url('gold') ?>">✦ GOLD</a>
        <?php endif; ?>
        <div class="ua"><?= esc($initials ?? strtoupper(substr((string) session('user_prenom'), 0, 1) . substr((string) session('user_nom'), 0, 1)) ?: 'NP') ?></div>
        <a class="btn-lo" href="<?= base_url('logout') ?>">Déconnexion</a>
    </div>
</nav>
<main class="container">