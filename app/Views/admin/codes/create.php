<?= view('admin/layout/header') ?>
<?= view('admin/layout/sidebar') ?>

<div class="admin-content">
<h1>Générer Code</h1>

<form method="post" action="<?= base_url('admin/codes') ?>">
    <label for="amount">Montant (Ar)</label>
    <input type="number" id="amount" name="amount" required>

    <button type="submit">Générer</button>
</form>
</div>