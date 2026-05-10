<?php
echo view('layout/header', ['navLinks' => null, 'extraStylesheets' => ['css/regimes.css']]);
?>

<section class="regimes-section container">
    
    <div class="regimes-header">
        <h1>CHOISIR VOTRE REGIME</h1>
        <p>Trouvez le regime adapte a vos objectifs</p>
    </div>

    <div class="regimes-grid">
        
        <?php foreach ($regimesList as $r): ?>
        <article class="regime-card">

            <?php if ($r['id'] == 2): ?>
            <div class="popular-badge">
                LE PLUS POPULAIRE
            </div>
            <?php endif; ?>

            <div class="regime-icon">
                <?php
                    $icon = "🍽️";

                    if ($r["id"] == 1) $icon = "🥗";
                    elseif ($r["id"] == 2) $icon = "⚖️";
                    elseif ($r["id"] == 3) $icon = "💪";

                    echo $icon;
                ?>
            </div>

            <div class="regime-content">

                <h3>
                    <?php echo htmlspecialchars($r["nom"] ?? "Regime"); ?>
                </h3>

                <p class="regime-description">
                    <?php echo htmlspecialchars($r["description"] ?? ""); ?>
                </p>

                <div class="nutrition-box">

                    <div class="nutrition-item">
                        <div class="nutrition-value">
                            <?php echo (int)($r["pourcentage_viande"] ?? 0); ?>%
                        </div>
                        <div class="nutrition-label">Viande</div>
                    </div>

                    <div class="nutrition-item">
                        <div class="nutrition-value">
                            <?php echo (int)($r["pourcentage_poisson"] ?? 0); ?>%
                        </div>
                        <div class="nutrition-label">Poisson</div>
                    </div>

                    <div class="nutrition-item">
                        <div class="nutrition-value">
                            <?php echo (int)($r["pourcentage_volaille"] ?? 0); ?>%
                        </div>
                        <div class="nutrition-label">Volaille</div>
                    </div>

                </div>

                <div class="regime-footer">

                    <div class="price-box">
                        <div class="price-label">
                            Prix par jour
                        </div>

                        <div class="price">
                            <?php echo number_format((float)($r["prix_journalier"] ?? 0), 0, ",", " "); ?> Ar
                        </div>
                    </div>

                    <form method="post" action="<?php echo base_url("regimes/souscrire"); ?>" class="subscribe-form">

                        <input type="hidden" name="regime_id" value="<?php echo (int)$r["id"]; ?>">

                        <input type="hidden" name="semaines" value="<?php echo (int)$regime_weeks; ?>">

                        <div class="weeks-input-box">

                            <input 
                                type="number"
                                name="custom_weeks"
                                value="<?php echo (int)$regime_weeks; ?>"
                                min="1"
                                max="52"
                                class="weeks-input"
                            >

                            <span>sem</span>

                        </div>

                        <button type="submit" class="subscribe-btn">
                            Souscrire
                        </button>

                    </form>

                </div>

            </div>

        </article>
        <?php endforeach; ?>

    </div>

</section>

<?php
echo view("layout/footer");
?>