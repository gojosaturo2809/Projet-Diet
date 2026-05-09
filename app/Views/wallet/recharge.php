<?= view('layout/header', ['title' => 'Recharge Portefeuille', 'active' => 'recharge']) ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert adang"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<div class="card">
    <div class="ch">
        <span class="ct">Recharger le portefeuille</span>
    </div>
    <p class="tmu" style="margin-bottom:1rem">Afficher saisie code</p>

    <form method="post" action="<?= base_url('wallet/add-money') ?>">
        <div class="form-group">
            <label for="code">Entrer un code recharge</label>
            <input id="code" name="code" type="text" class="form-control" placeholder="NP-2026-XYZ" required>
        </div>
        <button type="submit" class="btn bp">Valider le code</button>
    </form>
</div>

<?= view('layout/footer') ?>
