<?= view('admin/layout/header') ?>
<?= view('admin/layout/sidebar') ?>

<div class="admin-content">
    <h1>Régimes</h1>
    <a href="<?= base_url('admin/regimes/create') ?>" class="btn">+ Ajouter</a>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>NOM</th>
                    <th>VIANDE / POISSON / VOLAILLE</th>
                    <th>DURÉE</th>
                    <th>PRIX/SEM.</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($regimes as $regime): ?>
                    <tr>
                        <td><?= $regime['name'] ?></td>
                        <td><?= $regime['description'] ?></td>
                        <td><?= $regime['duration'] ?> sem.</td>
                        <td><?= number_format($regime['price'], 0, ',', ' ') ?> Ar</td>
                        <td>
                            <a href="<?= base_url('admin/regimes/' . $regime['id'] . '/edit') ?>">Éditer</a>
                            <a href="<?= base_url('admin/regimes/' . $regime['id'] . '/delete') ?>" class="btn-delete">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>