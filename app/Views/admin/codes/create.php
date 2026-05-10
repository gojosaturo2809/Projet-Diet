<?= view('admin/layout/header') ?>
<?= view('admin/layout/sidebar') ?>

<div class="admin-content">

<h1>➕ Générer Code</h1>

<form method="post" action="<?= base_url('admin/codes/store') ?>">

    <input type="number" name="montant" placeholder="Montant" required>

    <button type="submit">Générer</button>

</form>

</div>