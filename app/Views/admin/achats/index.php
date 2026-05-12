<?= view('admin/layout/header') ?>
<?= view('admin/layout/sidebar') ?>

<div class="admin-content">
    <h1>Achats en Attente de Confirmation</h1>

    <?php $successMessage = session()->getFlashdata('success'); ?>
    <?php $errorMessage = session()->getFlashdata('error'); ?>

    <?php if ($successMessage): ?>
        <div class="flash flash-success"><?= esc((string) $successMessage) ?></div>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
        <div class="flash flash-error"><?= esc((string) $errorMessage) ?></div>
    <?php endif; ?>

    <div class="table-card">
        <?php if (empty($achats)): ?>
            <p class="table-empty">Aucun achat en attente de confirmation.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Email</th>
                        <th>Régime</th>
                        <th>Prix</th>
                        <th>Semaines</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($achats as $achat): ?>
                        <tr>
                            <td><?= esc((string) (($achat['user_prenom'] ?? '') . ' ' . ($achat['user_nom'] ?? ''))) ?></td>
                            <td><?= esc((string) ($achat['email'] ?? '')) ?></td>
                            <td><?= esc((string) ($achat['regime_nom'] ?? '')) ?></td>
                            <td><?= number_format((float) ($achat['prix_paye'] ?? $achat['montant_total'] ?? 0), 0, ',', ' ') ?> Ar</td>
                            <td><?= esc((string) ($achat['semaines'] ?? $achat['duree_semaines'] ?? 0)) ?></td>
                            <td><?= isset($achat['date_achat']) ? date('d/m/Y H:i', strtotime($achat['date_achat'])) : '—' ?></td>
                            <td>
                                <div class="table-actions">
                                    <form method="post" style="display: inline;" action="<?= base_url('admin/achats/confirm/' . (int)($achat['id_achat'] ?? $achat['id'] ?? 0)) ?>">
                                        <button type="submit" class="btn-confirm" onclick="return confirm('Confirmer cet achat ?')">✓ Confirmer</button>
                                    </form>
                                    <form method="post" style="display: inline;" action="<?= base_url('admin/achats/reject/' . (int)($achat['id_achat'] ?? $achat['id'] ?? 0)) ?>">
                                        <input type="text" name="motif" placeholder="Motif..." class="motif-input" required>
                                        <button type="submit" class="btn-reject" onclick="return confirm('Rejeter cet achat ?')">✗ Rejeter</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
