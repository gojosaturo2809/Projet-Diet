<?= view('admin/layout/header') ?>
<?= view('admin/layout/sidebar') ?>

<div class="admin-content">
    <h1>Générer Code</h1>

    <?php $errors = $errors ?? []; ?>
    <?php $successMessage = session()->getFlashdata('success'); ?>
    <?php $errorMessage = session()->getFlashdata('error'); ?>

    <?php if ($successMessage): ?>
        <div class="flash flash-success"><?= esc((string) $successMessage) ?></div>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
        <div class="flash flash-error"><?= esc((string) $errorMessage) ?></div>
    <?php endif; ?>

    <?php if (! empty($errors)): ?>
        <div class="flash flash-error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= esc((string) $error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('admin/codes/store') ?>">
        <label for="montant">Montant (Ar)</label>
        <input type="number" id="montant" name="montant" value="<?= esc(old('montant')) ?>" required>

        <button type="submit">Générer</button>
    </form>
</div>