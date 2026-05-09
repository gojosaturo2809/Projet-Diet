<?= view('layout/header', ['title' => 'Portefeuille', 'active' => 'wallet']) ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert asucc"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert adang"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<div class="card">
    <div class="ch">
        <span class="ct">Portefeuille</span>
        <a class="btn bo" href="<?= base_url('wallet/recharge') ?>">+ Recharger</a>
    </div>
    <div class="balance"><?= number_format((float) ($solde ?? 0), 0, ',', ' ') ?> Ar</div>
    <p class="tmu">Afficher le solde utilisateur</p>
</div>

<?= view('layout/footer') ?>
