<?= view('admin/layout/header') ?>
<?= view('admin/layout/sidebar') ?>

<div class="admin-content">

    <h1>🥗 Régimes</h1>

    <a href="<?= base_url('admin/regimes/create') ?>" class="btn">
        ➕ Ajouter
    </a>

    <table border="1" width="100%">
        <tr>
            <th>Nom</th>
            <th>Prix</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($regimes as $r): ?>
        <tr>
            <td><?= $r['nom'] ?></td>
            <td><?= $r['prix_journalier'] ?> Ar</td>
            <td><?= $r['description'] ?></td>
            <td>
                <a href="<?= base_url('admin/regimes/delete/'.$r['id']) ?>"
                   onclick="return confirm('Supprimer ?')">
                   🗑
                </a>
            </td>
        </tr>
        <?php endforeach; ?>

    </table>

</div>