<?= $this->extend('base') ?>
<?= $this->section('content') ?>
<h2>Inscription — Étape 1 / Identité</h2>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <ul>
        <?php foreach (session()->getFlashdata('errors') as $e): ?>
            <li><?= esc($e) ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" action="<?= site_url('register/step1') ?>">
    <?= csrf_field() ?>
    <div>
        <label>Nom</label>
        <input type="text" name="nom" value="<?= esc(old('nom')) ?>" required>
    </div>
    <div>
        <label>Prénom</label>
        <input type="text" name="prenom" value="<?= esc(old('prenom')) ?>" required>
    </div>
    <div>
        <label>Email</label>
        <input type="email" name="email" value="<?= esc(old('email')) ?>" required>
    </div>
    <div>
        <label>Mot de passe</label>
        <input type="password" name="mot_de_passe" required minlength="8">
    </div>
    <button type="submit">Suivant</button>
</form>

<?= $this->endSection() ?>
