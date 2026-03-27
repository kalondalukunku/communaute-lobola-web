function switchTab(method) {
    const btnMobile = document.getElementById('btn-mobile');
    const btnCard = document.getElementById('btn-card');
    const paneMobile = document.getElementById('pane-mobile');
    const paneCard = document.getElementById('pane-card');
    const btnText = document.getElementById('btn-text');

    if (method === 'mobile') {
        btnMobile.classList.add('text-primary');
        btnMobile.classList.remove('text-gray-400');
        btnCard.classList.remove('text-primary');
        btnCard.classList.add('text-gray-400');
        paneMobile.classList.remove('hidden');
        paneCard.classList.add('hidden');
        btnText.innerText = "Payer";
    } else {
        btnCard.classList.add('text-primary');
        btnCard.classList.remove('text-gray-400');
        btnMobile.classList.remove('text-primary');
        btnMobile.classList.add('text-gray-400');
        paneCard.classList.remove('hidden');
        paneMobile.classList.add('hidden');
        btnText.innerText = "Payer par Carte";
    }
}

function processPayment() {
    const btn = document.getElementById('main-pay-btn');
    const btnText = document.getElementById('btn-text');
    const btnIcon = document.getElementById('btn-icon');
    
    // Simulation de chargement
    btn.disabled = true;
    btn.classList.add('opacity-80', 'cursor-not-allowed');
    btnText.innerText = "Traitement en cours...";
    btnIcon.className = "fas fa-circle-notch fa-spin text-[10px]";

    setTimeout(() => {
        btnText.innerText = "Vérification...";
        setTimeout(() => {
            // Ici vous redirigerez vers votre succès ou API
            btn.classList.replace('bg-primary', 'bg-green-600');
            btnText.innerText = "Transaction réussie !";
            btnIcon.className = "fas fa-check text-[10px]";
        }, 1500);
    }, 1500);
}