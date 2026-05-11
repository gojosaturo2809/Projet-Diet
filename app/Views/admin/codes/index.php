<?= view('admin/layout/header') ?>
<?= view('admin/layout/sidebar') ?>

<div class="admin-content">
<h1>Codes portefeuille</h1>
<a href="<?= base_url('admin/codes/create') ?>" class="btn">+ Générer code</a>

<div class="table-card">
<table>
    <thead>
        <tr>
            <th>CODE</th>
            <th>MONTANT</th>
            <th>UTILISÉ PAR</th>
            <th>STATUT</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($codes as $c): ?>
            <tr>
                <td><?= $c['code'] ?></td>
                <td><?= $c['amount'] ?> Ar</td>
                <td><?= $c['used_by'] ?? '—' ?></td>
                <td><?= isset($c['used_by']) ? 'Utilisé' : 'Disponible' ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
</div>