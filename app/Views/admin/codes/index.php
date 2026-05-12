<?= view('admin/layout/header') ?>
<?= view('admin/layout/sidebar') ?>

<div class="admin-content">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h1>Codes Portefeuille</h1>
        <a href="<?= base_url('admin/codes/create') ?>" class="btn">+ Générer Code</a>
    </div>

    <?php $codes = $codes ?? []; ?>
    <?php $successMessage = session()->getFlashdata('success'); ?>
    <?php $errorMessage = session()->getFlashdata('error'); ?>

    <?php if ($successMessage): ?>
        <div class="flash flash-success"><?= esc((string) $successMessage) ?></div>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
        <div class="flash flash-error"><?= esc((string) $errorMessage) ?></div>
    <?php endif; ?>

    <div class="table-card">
        <?php if (empty($codes)): ?>
            <p class="table-empty">Aucun code disponible.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Montant</th>
                        <th>Utilisé Par</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($codes as $c): ?>
                        <tr>
                            <td><strong><?= esc((string) ($c['code'] ?? '')) ?></strong></td>
                            <td><?= number_format((float) ($c['montant'] ?? 0), 0, ',', ' ') ?> Ar</td>
                            <td><?= esc((string) (trim((string) ($c['prenom'] ?? '') . ' ' . (string) ($c['nom'] ?? '')) ?: ($c['email'] ?? '—'))) ?></td>
                            <td>
                                <span class="badge" style="background: <?= ((int) ($c['is_active'] ?? 0) === 1) ? '#e8f5e9' : '#ffebee' ?>; color: <?= ((int) ($c['is_active'] ?? 0) === 1) ? '#2e7d32' : '#c62828' ?>;">
                                    <?= ((int) ($c['is_active'] ?? 0) === 1) ? 'Disponible' : 'Utilisé' ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?= base_url('admin/codes/' . $c['id'] . '/edit') ?>">Éditer</a>
                                <a href="<?= base_url('admin/codes/delete/' . $c['id']) ?>" class="btn-delete" onclick="return confirm('Supprimer ce code ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>