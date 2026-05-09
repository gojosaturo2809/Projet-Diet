document.addEventListener('DOMContentLoaded', () => {
    const state = window.__inscriptionState || {};
    const healthForm = document.querySelector('[data-health-form]');
    const accountPanel = document.querySelector('[data-panel="account"]');
    const healthPanel = document.querySelector('[data-panel="health"]');
    const backButton = document.querySelector('[data-back-to-health]');
    const healthMessage = document.querySelector('[data-health-message]');
    const imcValue = document.querySelector('[data-imc-value]');
    const imcCaption = document.querySelector('[data-imc-caption]');
    const poidsInput = document.querySelector('#poids');
    const tailleInput = document.querySelector('#taille');
    const stepIndicators = Array.from(document.querySelectorAll('[data-step-indicator]'));

    const labels = [
        { limit: 18.5, label: 'Insuffisance pondérale' },
        { limit: 25, label: 'Poids normal' },
        { limit: 30, label: 'Surpoids' },
        { limit: Infinity, label: 'Obésité' },
    ];

    const getLabel = (imc) => labels.find((entry) => imc < entry.limit).label;

    const setStep = (step) => {
        if (healthPanel) {
            healthPanel.classList.toggle('is-visible', step === 1);
        }

        if (accountPanel) {
            accountPanel.classList.toggle('is-visible', step === 2);
        }

        stepIndicators.forEach((indicator) => {
            const value = Number(indicator.dataset.stepIndicator || 0);
            indicator.classList.toggle('is-active', value <= step);
        });
    };

    const renderImc = (value) => {
        if (!imcValue || !imcCaption) {
            return;
        }

        if (!Number.isFinite(value)) {
            imcValue.textContent = '—';
            imcCaption.textContent = 'Renseignez votre poids et votre taille pour calculer votre IMC.';
            return;
        }

        imcValue.textContent = value.toFixed(1);
        imcCaption.textContent = getLabel(value);
    };

    const computeLocalImc = () => {
        const poids = Number.parseFloat(poidsInput?.value || '');
        const taille = Number.parseFloat(tailleInput?.value || '');

        if (!Number.isFinite(poids) || !Number.isFinite(taille) || taille <= 0) {
            renderImc(Number.NaN);
            return;
        }

        const tailleMetres = taille / 100;
        const imc = poids / (tailleMetres * tailleMetres);
        renderImc(imc);
    };

    const clearHealthErrors = () => {
        document.querySelectorAll('[data-error-for]').forEach((node) => {
            node.textContent = '';
            node.classList.remove('visible');
        });

        if (healthMessage) {
            healthMessage.textContent = '';
            healthMessage.classList.add('is-hidden');
        }
    };

    const showHealthErrors = (errors) => {
        if (!errors || typeof errors !== 'object') {
            return;
        }

        Object.entries(errors).forEach(([field, message]) => {
            const node = document.querySelector(`[data-error-for="${field}"]`);
            if (node) {
                node.textContent = message;
                node.classList.add('visible');
            }
        });
    };

    if (poidsInput) {
        poidsInput.addEventListener('input', computeLocalImc);
    }

    if (tailleInput) {
        tailleInput.addEventListener('input', computeLocalImc);
    }

    if (backButton) {
        backButton.addEventListener('click', () => setStep(1));
    }

    if (healthForm) {
        healthForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            clearHealthErrors();

            try {
                const response = await fetch(healthForm.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: new FormData(healthForm),
                });

                const payload = await response.json();

                if (!response.ok || payload.status !== 'success') {
                    if (payload.errors) {
                        showHealthErrors(payload.errors);
                    }

                    if (healthMessage) {
                        healthMessage.textContent = payload.message || 'Veuillez corriger les informations saisies.';
                        healthMessage.classList.remove('is-hidden');
                    }

                    return;
                }

                const health = payload.health || {};
                const imc = Number.parseFloat(health.imc);

                renderImc(Number.isFinite(imc) ? imc : Number.NaN);
                setStep(2);

                if (healthMessage) {
                    healthMessage.textContent = payload.message || 'Informations de santé enregistrées.';
                    healthMessage.classList.remove('is-hidden');
                }

                window.scrollTo({ top: 0, behavior: 'smooth' });
            } catch (error) {
                if (healthMessage) {
                    healthMessage.textContent = 'Impossible d\'enregistrer les informations de santé.';
                    healthMessage.classList.remove('is-hidden');
                }
            }
        });
    }

    if (state.initialStep === 2) {
        setStep(2);
    } else {
        setStep(1);
    }

    if (Number.isFinite(Number.parseFloat(state.imcValue))) {
        renderImc(Number.parseFloat(state.imcValue));
    } else if (poidsInput && tailleInput) {
        computeLocalImc();
    }
});
