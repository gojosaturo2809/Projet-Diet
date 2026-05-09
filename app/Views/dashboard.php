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
                    <h2>Régime Méditerranéen</h2>
                </div>
                <div class="dashboard-regime-actions" aria-label="Actions du régime">
                    <span class="btn bo dashboard-ghost-btn">↓ PDF</span>
                    <a class="btn bp" href="<?= site_url('objectifs') ?>">Changer</a>
                </div>
            </div>

            <p class="dashboard-regime-subtitle">Durée : 8 semaines — Réduction de poids</p>

            <div class="dashboard-regime-content">
                <div class="dashboard-regime-pills" aria-label="Répartition alimentaire">
                    <div class="dashboard-regime-pill">
                        <strong>30%</strong>
                        <span>Viande</span>
                    </div>
                    <div class="dashboard-regime-pill">
                        <strong>40%</strong>
                        <span>Poisson</span>
                    </div>
                    <div class="dashboard-regime-pill">
                        <strong>30%</strong>
                        <span>Volaille</span>
                    </div>
                </div>

                <div class="dashboard-regime-progress">
                    <div class="dashboard-regime-progress-head">
                        <span>Progression</span>
                        <strong>45%</strong>
                    </div>
                    <div class="dashboard-regime-progress-bar" aria-hidden="true">
                        <span></span>
                    </div>
                    <p>Semaine 4 sur 8 — Continuez !</p>
                </div>
            </div>
        </article>
    </div>
</section>

<?= view('layout/footer') ?>
