<?= view('admin/layout/header') ?>
<?= view('admin/layout/sidebar') ?>

<div class="admin-content">
    <h1>Modifier Régime</h1>

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

    <form method="post" action="<?= base_url('admin/regimes/' . ($regime['id'] ?? 0) . '/update') ?>">
        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom" value="<?= esc(old('nom', $regime['nom'] ?? '')) ?>" required>

        <label for="description">Description</label>
        <textarea id="description" name="description" required><?= esc(old('description', $regime['description'] ?? '')) ?></textarea>

        <label for="prix_journalier">Prix journalier (Ar)</label>
        <input type="number" step="0.01" id="prix_journalier" name="prix_journalier" value="<?= esc(old('prix_journalier', $regime['prix_journalier'] ?? '')) ?>" required>

        <label for="variation_poids_hebdo">Variation de poids hebdo</label>
        <input type="number" step="0.01" id="variation_poids_hebdo" name="variation_poids_hebdo" value="<?= esc(old('variation_poids_hebdo', $regime['variation_poids_hebdo'] ?? '')) ?>" required>

        <label for="poids_min_requis">Poids minimum requis</label>
        <input type="number" step="0.01" id="poids_min_requis" name="poids_min_requis" value="<?= esc(old('poids_min_requis', $regime['poids_min_requis'] ?? 0)) ?>">

        <label for="pourcentage_viande">Composition viande (%)</label>
        <input type="number" min="0" max="100" id="pourcentage_viande" name="pourcentage_viande" value="<?= esc(old('pourcentage_viande', $regime['pourcentage_viande'] ?? 0)) ?>">

        <label for="pourcentage_poisson">Composition poisson (%)</label>
        <input type="number" min="0" max="100" id="pourcentage_poisson" name="pourcentage_poisson" value="<?= esc(old('pourcentage_poisson', $regime['pourcentage_poisson'] ?? 0)) ?>">

        <label for="pourcentage_volaille">Composition volaille (%)</label>
        <input type="number" min="0" max="100" id="pourcentage_volaille" name="pourcentage_volaille" value="<?= esc(old('pourcentage_volaille', $regime['pourcentage_volaille'] ?? 0)) ?>">

        <button type="submit">Mettre à jour</button>
    </form>
</div>