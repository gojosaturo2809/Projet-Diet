<?php
echo view('layout/header', ['navLinks' => null, 'extraStylesheets' => ['css/style.css', 'css/regimes.css']]);
?>

<section class="regimes-section container">
    
    <div class="regimes-header">
        <h1>CHOISIR VOTRE REGIME</h1>
        <p>Trouvez le regime adapte a vos objectifs</p>
        
        <div class="balance-info">
            <span class="balance-label">Solde actuel:</span>
            <span class="balance-amount" id="balanceDisplay"><?php echo number_format($solde, 0, ',', ' '); ?> Ar</span>
        </div>
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

                    <a href="<?php echo site_url('regimes/detail?id=' . (int)$r['id']); ?>" class="view-detail-btn" title="Voir détail et activités recommandées">
                        👁️ Voir
                    </a>

                    <form method="post" action="<?php echo base_url("regimes/souscrire"); ?>" class="subscribe-form" data-price-per-day="<?php echo (float)($r["prix_journalier"] ?? 0); ?>" data-is-gold="<?php echo $isGoldUser ? '1' : '0'; ?>" data-solde="<?php echo $solde; ?>">

                        <input type="hidden" name="regime_id" value="<?php echo (int)$r["id"]; ?>">

                        <input type="hidden" name="semaines" class="weeks-hidden" value="<?php echo (int)$regime_weeks; ?>">

                        <div class="weeks-input-box">

                            <input 
                                type="number"
                                name="custom_weeks"
                                value="<?php echo (int)$regime_weeks; ?>"
                                min="1"
                                max="52"
                                class="weeks-input"
                                data-regime-id="<?php echo (int)$r["id"]; ?>"
                            >

                            <span>sem</span>

                        </div>

                        <div class="estimated-price-box">
                            <div class="estimated-label">Coût estimé:</div>
                            <div class="estimated-price" data-base-price="<?php echo (float)($r["prix_journalier"] ?? 0); ?>">
                                <?php $est = ((float)($r["prix_journalier"] ?? 0)) * $regime_weeks * 7; 
                                      if ($isGoldUser) $est = $est * 0.85;
                                      echo number_format($est, 0, ',', ' '); ?> Ar
                            </div>
                        </div>

                        <button 
                            type="submit" 
                            class="subscribe-btn" 
                            data-regime-id="<?php echo (int)$r["id"]; ?>"
                        >
                            Souscrire
                        </button>

                    </form>

                </div>

            </div>

        </article>
        <?php endforeach; ?>

    </div>

</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const weeksInputs = document.querySelectorAll('.weeks-input');
    
    weeksInputs.forEach(input => {
        input.addEventListener('change', function() {
            updatePriceAndButtonState(this);
        });
        
        input.addEventListener('input', function() {
            updatePriceAndButtonState(this);
        });
        
        // Initial state check
        updatePriceAndButtonState(input);
    });
});

function updatePriceAndButtonState(weeksInput) {
    const form = weeksInput.closest('.subscribe-form');
    const regimeId = weeksInput.dataset.regimeId;
    const weeks = Math.max(1, parseInt(weeksInput.value) || 0);
    
    // Update hidden input
    const hiddenInput = form.querySelector('.weeks-hidden');
    if (hiddenInput) {
        hiddenInput.value = weeks;
    }
    
    // Get form data
    const pricePerDay = parseFloat(form.dataset.pricePerDay);
    const isGold = form.dataset.isGold === '1';
    const solde = parseFloat(form.dataset.solde);
    
    // Calculate estimated price
    const days = weeks * 7;
    let estimatedPrice = pricePerDay * days;
    
    if (isGold) {
        estimatedPrice = estimatedPrice * 0.85; // 15% discount
    }
    
    // Update estimated price display
    const estimatedPriceEl = form.querySelector('.estimated-price');
    if (estimatedPriceEl) {
        estimatedPriceEl.textContent = formatNumber(estimatedPrice) + ' Ar';
    }
    
    // Update button state
    const button = form.querySelector('.subscribe-btn');
    if (button) {
        if (estimatedPrice > solde) {
            button.disabled = true;
            button.classList.add('insufficient-balance');
            button.title = 'Solde insuffisant (' + formatNumber(solde) + ' Ar disponible)';
            button.textContent = '❌ Solde insuffisant';
        } else {
            button.disabled = false;
            button.classList.remove('insufficient-balance');
            button.title = 'Cliquez pour souscrire';
            button.textContent = 'Souscrire';
        }
    }
}

function formatNumber(num) {
    return Math.round(num).toLocaleString('fr-FR').replace(/\s/g, ' ');
}
</script>

<?php
echo view("layout/footer");
?>