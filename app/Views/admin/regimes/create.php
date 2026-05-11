<?= view('admin/layout/header') ?>
<?= view('admin/layout/sidebar') ?>

<div class="admin-content">
<h1>Ajouter Régime</h1>

<form method="post" action="<?= base_url('admin/regimes') ?>">
    <label for="name">Nom</label>
    <input type="text" id="name" name="name" required>

    <label for="description">Description</label>
    <textarea id="description" name="description" required></textarea>

    <label for="duration">Durée (semaines)</label>
    <input type="number" id="duration" name="duration" required>

    <label for="price">Prix (Ar)</label>
    <input type="number" id="price" name="price" required>

    <button type="submit">Créer</button>
</form>
</div>