<?= view('admin/layout/header') ?>
<?= view('admin/layout/sidebar') ?>

<div class="admin-content">
    <h1>Modifier Code</h1>

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

    <form method="post" action="<?= base_url('admin/codes/' . ($code['id'] ?? 0) . '/update') ?>">
        <label for="code">Code</label>
        <input type="text" id="code" value="<?= esc((string) ($code['code'] ?? '')) ?>" disabled>

        <label for="montant">Montant (Ar)</label>
        <input type="number" id="montant" name="montant" value="<?= esc((string) old('montant', $code['montant'] ?? '')) ?>" required>

        <label>Statut</label>
        <div style="display:flex; gap:12px; flex-wrap:wrap; margin-bottom: 12px;">
            <label>
                <input type="radio" name="is_active" value="1" <?= old('is_active', (string) ($code['is_active'] ?? '1')) === '1' ? 'checked' : '' ?>>
                Disponible
            </label>
            <label>
                <input type="radio" name="is_active" value="0" <?= old('is_active', (string) ($code['is_active'] ?? '1')) === '0' ? 'checked' : '' ?>>
                Utilisé
            </label>
        </div>

        <button type="submit">Mettre à jour</button>
    </form>
</div>