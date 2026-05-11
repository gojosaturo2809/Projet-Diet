<?= view('admin/layout/header') ?>
<?= view('admin/layout/sidebar') ?>

<div class="admin-content">
    <h1>Régimes</h1>
    <a href="<?= base_url('admin/regimes/create') ?>" class="btn">+ Ajouter</a>

    <?php $regimes = $regimes ?? []; ?>
    <?php $successMessage = session()->getFlashdata('success'); ?>
    <?php $errorMessage = session()->getFlashdata('error'); ?>

    <?php if ($successMessage): ?>
        <div class="flash flash-success"><?= esc((string) $successMessage) ?></div>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
        <div class="flash flash-error"><?= esc((string) $errorMessage) ?></div>
    <?php endif; ?>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>NOM</th>
                    <th>DESCRIPTION</th>
                    <th>PRIX JOURNALIER</th>
                    <th>VARIATION/SEMAINE</th>
                    <th>POIDS MIN.</th>
                    <th>COMPOSITION</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($regimes as $regime): ?>
                    <tr>
                        <td><?= esc((string) ($regime['nom'] ?? '')) ?></td>
                        <td><?= esc((string) ($regime['description'] ?? '')) ?></td>
                        <td><?= number_format((float) ($regime['prix_journalier'] ?? 0), 0, ',', ' ') ?> Ar</td>
                        <td><?= esc((string) ($regime['variation_poids_hebdo'] ?? 0)) ?></td>
                        <td><?= esc((string) ($regime['poids_min_requis'] ?? 0)) ?></td>
                        <td>
                            V: <?= esc((string) ($regime['pourcentage_viande'] ?? 0)) ?>%
                            / P: <?= esc((string) ($regime['pourcentage_poisson'] ?? 0)) ?>%
                            / W: <?= esc((string) ($regime['pourcentage_volaille'] ?? 0)) ?>%
                        </td>
                        <td>
                            <a href="<?= base_url('admin/regimes/' . $regime['id'] . '/edit') ?>">Éditer</a>
                            <a href="<?= base_url('admin/regimes/delete/' . $regime['id']) ?>" class="btn-delete" onclick="return confirm('Supprimer ce régime ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>