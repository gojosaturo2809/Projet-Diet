<?= view('admin/layout/header') ?>
<?= view('admin/layout/sidebar') ?>

<div class="admin-content">

<h1>🎟 Codes portefeuille</h1>

<a href="<?= base_url('admin/codes/create') ?>">➕ Générer code</a>

<table border="1" width="100%">

<tr>
    <th>Code</th>
    <th>Montant</th>
    <th>Statut</th>
</tr>

<?php foreach ($codes as $c): ?>
<tr>
    <td><?= $c['code'] ?></td>
    <td><?= $c['montant'] ?> Ar</td>
    <td>
        <?= isset($c['used_by']) ? 'Utilisé' : 'Disponible' ?>
    </td>
</tr>
<?php endforeach; ?>

</table>

</div>