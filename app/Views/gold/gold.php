<?= view('layout/header', ['title' => 'Option Gold', 'active' => 'gold']) ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert asucc"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>

<div class="goldban">
    <div>
        <h2 style="font-family:var(--font-d);margin-bottom:6px">Option Gold</h2>
        <p>Afficher avantages et prix: 15% de reduction sur tous les regimes a vie.</p>
    </div>
    <?php if (!empty($isGold)): ?>
        <span class="btn" style="background:var(--gold-light);color:#6b4b00;border-color:transparent">Gold deja active</span>
    <?php else: ?>
        <form method="post" action="<?= base_url('gold/activate') ?>">
            <button type="submit" class="btn bgold">Activer Gold - 25 000 Ar</button>
        </form>
    <?php endif; ?>
</div>

<?= view('layout/footer') ?>
