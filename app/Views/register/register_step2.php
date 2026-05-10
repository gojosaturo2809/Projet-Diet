<?= $this->extend('base') ?>
<?= $this->section('content') ?>
<h2>Inscription — Étape 2 / Informations de santé</h2>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <ul>
        <?php foreach (session()->getFlashdata('errors') as $e): ?>
            <li><?= esc($e) ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" action="<?= site_url('register/step2') ?>">
    <?= csrf_field() ?>
    <div>
        <label>Poids (kg)</label>
        <input type="number" step="0.1" name="poids" value="<?= esc(old('poids')) ?>" required>
    </div>
    <div>
        <label>Taille (cm)</label>
        <input type="number" step="0.1" name="taille" value="<?= esc(old('taille')) ?>" required>
    </div>
    <div>
        <a href="<?= site_url('register/step1') ?>">Retour</a>
        <button type="submit">Finaliser l'inscription</button>
    </div>
</form>

<?= $this->endSection() ?>
