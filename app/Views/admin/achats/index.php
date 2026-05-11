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
            <p style="padding: 20px; text-align: center; color: #999;">Aucun achat en attente de confirmation.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>UTILISATEUR</th>
                        <th>EMAIL</th>
                        <th>RÉGIME</th>
                        <th>PRIX</th>
                        <th>SEMAINES</th>
                        <th>DATE</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($achats as $achat): ?>
                        <tr>
                            <td><?= esc((string) (($achat['user_prenom'] ?? '') . ' ' . ($achat['user_nom'] ?? ''))) ?></td>
                            <td><?= esc((string) ($achat['email'] ?? '')) ?></td>
                            <td><?= esc((string) ($achat['regime_nom'] ?? '')) ?></td>
                            <td><?= number_format((float) ($achat['prix_paye'] ?? 0), 0, ',', ' ') ?> Ar</td>
                            <td><?= esc((string) ($achat['semaines'] ?? 0)) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($achat['date_achat'] ?? 'now')) ?></td>
                            <td>
                                <form method="post" style="display: inline;" action="<?= base_url('admin/achats/confirm/' . (int)($achat['id'] ?? 0)) ?>">
                                    <button type="submit" class="btn-confirm" onclick="return confirm('Confirmer cet achat ?')">✓ Confirmer</button>
                                </form>
                                <form method="post" style="display: inline;" action="<?= base_url('admin/achats/reject/' . (int)($achat['id'] ?? 0)) ?>">
                                    <input type="text" name="motif" placeholder="Motif" class="motif-input" required>
                                    <button type="submit" class="btn-reject" onclick="return confirm('Rejeter cet achat ?')">✗ Rejeter</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
