(function () {
    if (window.__lobolaAppInitialized) {
        return;
    }

    window.__lobolaAppInitialized = true;
    const loadingClass = 'page-navigation-loading';

    function getPageLoader() {
        return document.getElementById('spiritual-loader');
    }

    function showPageLoader() {
        const loader = getPageLoader();
        if (!loader) {
            return;
        }

        document.body.classList.add('loading', loadingClass);
        loader.style.display = 'flex';
        loader.style.opacity = '1';
    }

    function hidePageLoader() {
        const loader = getPageLoader();
        if (!loader) {
            return;
        }

        loader.style.opacity = '0';
        document.body.classList.remove('loading', loadingClass);
        window.setTimeout(function () {
            if (!document.body.classList.contains(loadingClass)) {
                loader.style.display = 'none';
            }
        }, 700);
    }

    function setControlLoadingState(control, disableControl) {
        if (control.classList.contains('submit-loading')) {
            return;
        }

        if (disableControl) {
            if (!control.disabled) {
                control.dataset.loaderDisabled = 'true';
            }
            control.disabled = true;
        }
        control.setAttribute('aria-busy', 'true');
        control.classList.add('submit-loading');

        if (control.tagName === 'BUTTON' || control.tagName === 'A') {
            const spinner = document.createElement('span');
            spinner.className = 'submit-loader';
            spinner.setAttribute('aria-hidden', 'true');
            control.prepend(spinner);
        } else {
            control.dataset.submitValue = control.value;
            control.value = 'Chargement...';
        }
    }

    function setSubmitLoadingState(form) {
        const submitControls = form.querySelectorAll('button:not([type]), button[type="submit"], input[type="submit"]');

        submitControls.forEach(function (control) {
            setControlLoadingState(control, false);
        });
    }

    function resetSubmitLoadingStates() {
        document.querySelectorAll('.submit-loading').forEach(function (control) {
            resetControlLoadingState(control);
        });
        document.querySelectorAll('form[data-submitting="true"]').forEach(function (form) {
            delete form.dataset.submitting;
        });
    }

    function resetControlLoadingState(control) {
        if (control.dataset.loaderDisabled === 'true') {
            control.disabled = false;
            delete control.dataset.loaderDisabled;
        }
        control.removeAttribute('aria-busy');
        control.classList.remove('submit-loading');

        const spinner = control.querySelector('.submit-loader');
        if (spinner) {
            spinner.remove();
        }

        if (control.dataset.submitValue) {
            control.value = control.dataset.submitValue;
            delete control.dataset.submitValue;
        }
    }

    function resetFormLoadingState(form) {
        form.querySelectorAll('.submit-loading').forEach(resetControlLoadingState);
        delete form.dataset.submitting;
    }

    window.LobolaLoaders = {
        start: setSubmitLoadingState,
        stop: resetSubmitLoadingStates,
        stopForm: resetFormLoadingState
    };

    function initialize() {
        const quote = document.getElementById('loader-quote');
        const quotes = [
            'La Maât guide vos pas',
            'La lumière est en vous',
            'Écoutez la voie de vos ancêtres',
            'Devenez des maîtres de votre destin',
            'Honorez la Terre et les Cieux de vos ancêtres',
            'Que la sagesse de Maât vous accompagne',
            'Ensemble, nous sommes plus forts',
            'Que la paix soit avec vous'
        ];

        if (quote) {
            quote.innerText = quotes[Math.floor(Math.random() * quotes.length)];
            quote.style.opacity = '1';
        }

        document.addEventListener('submit', function (event) {
            if (!event.target.matches('form')) {
                return;
            }

            if (event.target.dataset.submitting === 'true') {
                event.preventDefault();
                return;
            }

            event.target.dataset.submitting = 'true';
            setSubmitLoadingState(event.target);
            window.setTimeout(function () {
                if (!event.defaultPrevented) {
                    showPageLoader();
                } else if (!event.target.matches('.comment-form')) {
                    resetSubmitLoadingStates();
                }
            }, 0);
        }, true);

        document.addEventListener('click', function (event) {
            const downloadLink = event.target.closest('a[download]');
            if (!downloadLink || downloadLink.classList.contains('submit-loading')) {
                return;
            }

            setControlLoadingState(downloadLink, true);
            window.setTimeout(function () {
                resetSubmitLoadingStates();
            }, 1500);
        });

        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                resetSubmitLoadingStates();
                hidePageLoader();
            }
        });

        window.addEventListener('load', function () {
            window.setTimeout(hidePageLoader, 1000);
        });
    }

    document.body.classList.add('loading');

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initialize);
    } else {
        initialize();
    }
})();
