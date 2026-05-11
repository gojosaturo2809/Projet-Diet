<?= view('admin/layout/header') ?>
<?= view('admin/layout/sidebar') ?>

<div class="admin-content">
    <h1>Codes portefeuille</h1>
    <a href="<?= base_url('admin/codes/create') ?>" class="btn">+ Générer code</a>

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
        <table>
            <thead>
                <tr>
                    <th>CODE</th>
                    <th>MONTANT</th>
                    <th>UTILISÉ PAR</th>
                    <th>STATUT</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($codes as $c): ?>
                    <tr>
                        <td><?= esc((string) ($c['code'] ?? '')) ?></td>
                        <td><?= number_format((float) ($c['montant'] ?? 0), 0, ',', ' ') ?> Ar</td>
                        <td>
                            <?= esc((string) (trim((string) ($c['prenom'] ?? '') . ' ' . (string) ($c['nom'] ?? '')) ?: ($c['email'] ?? '—'))) ?>
                        </td>
                        <td><?= ((int) ($c['is_active'] ?? 0) === 1) ? 'Disponible' : 'Utilisé' ?></td>
                        <td>
                            <a href="<?= base_url('admin/codes/' . $c['id'] . '/edit') ?>">Éditer</a>
                            <a href="<?= base_url('admin/codes/delete/' . $c['id']) ?>" class="btn-delete" onclick="return confirm('Supprimer ce code ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>