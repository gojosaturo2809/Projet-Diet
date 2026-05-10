<?= view('layout/header', [
    'title' => 'Dashboard - NutriPlan',
    'active' => 'dashboard',
    'extraStylesheets' => ['css/dashboard.css'],
    'navLinks' => $navLinks ?? null,
    'solde' => $solde ?? null,
    'isGold' => $isGold ?? false,
    'initials' => $initials ?? 'NP',
]) ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert asucc"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>

<section class="dashboard-shell" id="top">
    <header class="dashboard-intro">
        <p class="dashboard-kicker">ESPACE PERSONNEL</p>
        <h1>Bonjour, <?= esc($user['prenom'] ?? 'Utilisateur') ?> 👋</h1>
        <p class="dashboard-lead">Voici un résumé de votre programme santé.</p>
    </header>

    <div class="dashboard-topgrid">
        <article class="imc dashboard-imc">
            <p class="il">Votre IMC actuel</p>
            <div class="iv"><?= $imc !== null ? esc(number_format((float) $imc, 1, ',', ' ')) : '—' ?></div>
            <p class="ic"><?= esc($imcLabel ?? 'Renseignez vos données de santé') ?></p>
            <div class="isc dashboard-scale" aria-hidden="true">
                <div class="scale-under"></div>
                <div class="scale-normal"></div>
                <div class="scale-over"></div>
                <div class="scale-obese"></div>
            </div>
            <div class="dashboard-scale-labels">
                <span>Maigreur</span>
                <span>Normal ✓</span>
                <span>Surpoids</span>
                <span>Obésité</span>
            </div>
        </article>
        <!-- Section Choix des régimes -->
        <article class="card dashboard-regime-card" id="regimes" style="margin-top:2rem">
            <div class="dashboard-regime-head">
                <div>
                    <p class="dashboard-card-kicker">Choisir un régime</p>
                    <h2>Régimes disponibles</h2>
                </div>
                <div class="dashboard-regime-actions">
                    <a class="btn bo" href="<?= site_url('regimes/download-pdf') ?>?semaines=<?= (int) ($regime_weeks ?? 4) ?>" title="Télécharger le PDF">⬇️ PDF</a>
                    <a class="btn bo" href="<?= site_url('regimes/print') ?>?semaines=<?= (int) ($regime_weeks ?? 4) ?>" title="Imprimer">🖨️ Imprimer</a>
                </div>
            </div>

            <p class="dashboard-regime-subtitle">Sélectionnez le régime et l'activité qui vous conviennent.</p>

            <?php if (!empty($regimesList) && is_array($regimesList)): ?>
                <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(300px, 1fr));gap:1rem">
                    <?php foreach ($regimesList as $r): ?>
                        <?php
                            $jours = ((int) ($regime_weeks ?? 4)) * 7;
                            $prixBase = (float) ($r['prix_journalier'] ?? 0) * $jours;
                            $isGold = !empty($isGold);
                            $prixFinal = $isGold ? round($prixBase * 0.85, 0) : round($prixBase, 0);
                        ?>
                        <div style="background:var(--dark-50);border:1px solid var(--dark-200);border-radius:var(--r-md);padding:1rem">
                            <h3 style="margin:0 0 .5rem"><?= esc($r['nom'] ?? '—') ?></h3>
                            <div style="font-size:.9rem;color:var(--dark-600);margin-bottom:.6rem"><?= esc($r['description'] ?? '') ?></div>
                            <div style="display:flex;gap:1rem;align-items:center;margin-bottom:.6rem">
                                <div><strong><?= number_format($prixFinal, 0, ',', ' ') ?> Ar</strong><div style="font-size:.8rem;color:var(--dark-500)">pour <?= (int) ($regime_weeks ?? 4) ?> semaines</div></div>
                                <div style="flex:1;text-align:right">
                                    <form method="post" action="<?= site_url('regimes/souscrire') ?>">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="regime_id" value="<?= (int) $r['id'] ?>">
                                        <input type="hidden" name="semaines" value="<?= (int) ($regime_weeks ?? 4) ?>">
                                        <button class="btn bp" type="submit">Choisir ce régime</button>
                                    </form>
                                </div>
                            </div>
                            <div style="font-size:.85rem;color:var(--dark-600)">Composition:
                                <span style="margin-left:.6rem">Viande <?= esc($r['pourcentage_viande'] ?? '—') ?>%</span>
                                <span style="margin-left:.6rem">Poisson <?= esc($r['pourcentage_poisson'] ?? '—') ?>%</span>
                                <span style="margin-left:.6rem">Volaille <?= esc($r['pourcentage_volaille'] ?? '—') ?>%</span>
                            </div>
                            <?php if (!empty($r['suggested_sport'])): ?>
                                <div style="margin-top:.6rem;font-size:.85rem;color:var(--dark-600)">Sport suggéré: <strong><?= esc($r['suggested_sport']['nom'] ?? $r['suggested_sport']['nom_sport'] ?? '—') ?></strong> — <?= esc($r['suggested_sport']['intensite'] ?? '—') ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div style="padding:1.5rem;color:var(--dark-600)">Aucun régime disponible pour le moment.</div>
            <?php endif; ?>
        </article>

        <div class="dashboard-side">
            <div class="dashboard-mini-grid">
                <article class="sc dashboard-stat-card">
                    <div class="sv"><?= $poids !== null ? esc(number_format((float) $poids, 0, ',', ' ')) . ' kg' : '—' ?></div>
                    <div class="sl">Poids actuel</div>
                </article>
                <article class="sc dashboard-stat-card">
                    <div class="sv"><?= $taille !== null ? esc(number_format((float) $taille, 0, ',', ' ')) . ' cm' : '—' ?></div>
                    <div class="sl">Taille</div>
                </article>
            </div>

            <article class="card dashboard-wallet-card">
                <div class="ch">
                    <span class="ct">Portefeuille</span>
                    <a class="btn bo" href="<?= base_url('wallet/recharge') ?>">+ Recharger</a>
                </div>
                <div class="balance"><?= number_format((float) ($solde ?? 0), 0, ',', ' ') ?> Ar</div>
                <p class="tmu">Entrez un code de recharge pour ajouter des fonds</p>
            </article>
        </div>
    </div>

    <div class="dashboard-bottomgrid">
        <article class="card dashboard-regime-card" id="regime-actif">
            <div class="dashboard-regime-head">
                    <div>
                        <p class="dashboard-card-kicker">Mon régime actif</p>
                        <h2><?= isset($activeRegime['nom']) ? esc($activeRegime['nom']) : 'Aucun régime' ?></h2>
                    </div>
                    <div class="dashboard-regime-actions" aria-label="Actions du régime">
                        <span class="btn bo dashboard-ghost-btn">↓ PDF</span>
                        <a class="btn bp" href="<?= site_url('objectifs') ?>">Changer</a>
                    </div>
                </div>

                <p class="dashboard-regime-subtitle"><?= isset($activeRegime['subtitle']) ? esc($activeRegime['subtitle']) : 'Aucune suggestion disponible' ?></p>

                <div class="dashboard-regime-content">
                    <div class="dashboard-regime-pills" aria-label="Répartition alimentaire">
                        <div class="dashboard-regime-pill">
                            <strong><?= isset($activeRegime['pourcentage_viande']) ? esc($activeRegime['pourcentage_viande']) . '%' : '—' ?></strong>
                            <span>Viande</span>
                        </div>
                        <div class="dashboard-regime-pill">
                            <strong><?= isset($activeRegime['pourcentage_poisson']) ? esc($activeRegime['pourcentage_poisson']) . '%' : '—' ?></strong>
                            <span>Poisson</span>
                        </div>
                        <div class="dashboard-regime-pill">
                            <strong><?= isset($activeRegime['pourcentage_volaille']) ? esc($activeRegime['pourcentage_volaille']) . '%' : '—' ?></strong>
                            <span>Volaille</span>
                        </div>
                    </div>

                    <div class="dashboard-regime-progress">
                        <div class="dashboard-regime-progress-head">
                            <span>Progression</span>
                            <strong><?= isset($activeRegime['progress']) ? esc($activeRegime['progress']) . '%' : '—' ?></strong>
                        </div>
                        <div class="dashboard-regime-progress-bar" aria-hidden="true">
                            <span style="width:<?= isset($activeRegime['progress']) ? (int)$activeRegime['progress'] . '%' : '0%' ?>"></span>
                        </div>
                        <p><?= isset($activeRegime['semaine_actuelle']) ? 'Semaine ' . (int)$activeRegime['semaine_actuelle'] . ' sur ' . (int)$activeRegime['total_semaines'] : '' ?><?= !isset($activeRegime['semaine_actuelle']) ? ' — Commencez votre objectif' : '' ?></p>
                    </div>
                </div>
        </article>
    </div>
</section>

<?= view('layout/footer') ?>
