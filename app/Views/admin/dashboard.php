<?= view('admin/layout/header') ?>
<?= view('admin/layout/sidebar') ?>

<div class="admin-content">

    <h1>📊 Dashboard Admin</h1>

    <!-- KPIs -->
    <div class="kpi-grid">

        <div class="kpi">
            <h2><?= $totalUsers ?></h2>
            <p>Utilisateurs</p>
        </div>

        <div class="kpi">
            <h2><?= $totalRegimes ?></h2>
            <p>Régimes</p>
        </div>

        <div class="kpi">
            <h2><?= $totalCodes ?></h2>
            <p>Codes</p>
        </div>

        <div class="kpi">
            <h2><?= number_format($revenuTotal,0,',',' ') ?> Ar</h2>
            <p>Revenus</p>
        </div>

    </div>

    <!-- GRAPHIQUES -->
    <div class="charts">

        <div class="chart-box">
            <h3>📈 Inscriptions mensuelles</h3>
            <canvas id="chartUsers"></canvas>
        </div>

        <div class="chart-box">
            <h3>🥗 Objectifs régimes</h3>
            <canvas id="chartRegimes"></canvas>
        </div>

    </div>

</div>

<!-- ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const usersData = <?= json_encode($usersMonthly ?? []) ?>;
const regimesData = <?= json_encode($regimesObjectif ?? []) ?>;

/* 📈 USERS */
new Chart(document.getElementById('chartUsers'), {
    type: 'line',
    data: {
        labels: usersData.map(u => "M" + u.mois),
        datasets: [{
            label: 'Inscriptions',
            data: usersData.map(u => u.total),
            borderWidth: 2
        }]
    }
});

/* 🥗 REGIMES */
new Chart(document.getElementById('chartRegimes'), {
    type: 'pie',
    data: {
        labels: regimesData.map(r => r.objectif),
        datasets: [{
            data: regimesData.map(r => r.total)
        }]
    }
});
</script>