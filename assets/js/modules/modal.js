const openBtn = document.getElementById('openModalBtn');
const closeBtn = document.getElementById('closeModalBtn');
const closeIcon = document.getElementById('closeIcon');
const modalOverlay = document.getElementById('modalOverlay');
const body = document.body;

// Fonction pour ouvrir le modal
function openModal() {
    modalOverlay.classList.remove('hidden');
    body.classList.add('modal-active');
}

// Fonction pour fermer le modal
function closeModal() {
    modalOverlay.classList.add('hidden');
    body.classList.remove('modal-active');
}

// Événements
openBtn.addEventListener('click', openModal);
closeBtn.addEventListener('click', closeModal);
closeIcon.addEventListener('click', closeModal);

// Optionnel : Fermer avec la touche Echap
window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !modalOverlay.classList.contains('hidden')) {
        closeModal();
    }
});

/* Note : Nous n'ajoutons pas d'écouteur sur 'modalOverlay' 
    pour empêcher la fermeture au clic à l'extérieur.
*/