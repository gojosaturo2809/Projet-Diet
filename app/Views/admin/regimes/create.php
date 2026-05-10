<?= view('admin/layout/header') ?>
<?= view('admin/layout/sidebar') ?>

<div class="admin-content">

<h1>➕ Ajouter Régime</h1>

<form method="post" action="<?= base_url('admin/regimes/store') ?>">

    <input type="text" name="nom" placeholder="Nom" required>

    <textarea name="description" placeholder="Description"></textarea>

    <input type="number" name="prix_journalier" placeholder="Prix journalier">

    <input type="number" name="variation_poids_hebdo" placeholder="Variation poids">

    <input type="number" name="poids_min_requis" placeholder="Poids min">

    <h3>Composition %</h3>

    <input type="number" name="pourcentage_viande" placeholder="Viande">
    <input type="number" name="pourcentage_poisson" placeholder="Poisson">
    <input type="number" name="pourcentage_volaille" placeholder="Volaille">

    <button type="submit">Enregistrer</button>

</form>

</div>