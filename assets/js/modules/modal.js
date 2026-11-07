const openBtn = document.getElementById('openModalBtn');
const openBtn2 = document.getElementById('openModalBtn2');
const openBtnDelete = document.getElementById('openModalBtnDelete');
const closeBtn = document.getElementById('closeModalBtn');
const closeBtn2 = document.getElementById('closeModalBtn2');
const closeBtnDelete = document.getElementById('closeModalBtnDelete');
const closeIcon = document.getElementById('closeIcon');
const closeIcon2 = document.getElementById('closeIcon2');
const closeIconDelete = document.getElementById('closeIconDelete');
const modalOverlay = document.getElementById('modalOverlay');
const modalOverlay2 = document.getElementById('modalOverlay2');
const modalOverlayDelete = document.getElementById('modalOverlayDelete');
const body = document.body;

// Fonction pour ouvrir le modal
function openModal(modal) {
    modal.classList.remove('hidden');
    body.classList.add('modal-active');
}

// Fonction pour fermer le modal
function closeModal(modal) {
    modal.classList.add('hidden');
    body.classList.remove('modal-active');
}

// Événements
if(openBtn) openBtn.addEventListener('click', () => openModal(modalOverlay));
if(openBtn2) openBtn2.addEventListener('click', () => openModal(modalOverlay2));
if(openBtnDelete) openBtnDelete.addEventListener('click', () => openModal(modalOverlayDelete));
if(closeBtn) closeBtn.addEventListener('click', () => closeModal(modalOverlay));
if(closeBtn2) closeBtn2.addEventListener('click', () => closeModal(modalOverlay2));
if(closeBtnDelete) closeBtnDelete.addEventListener('click', () => closeModal(modalOverlayDelete));
if(closeIcon) closeIcon.addEventListener('click', () => closeModal(modalOverlay));
if(closeIcon2) closeIcon2.addEventListener('click', () => closeModal(modalOverlay2));
if(closeIconDelete) closeIconDelete.addEventListener('click', () => closeModal(modalOverlayDelete));

// Optionnel : Fermer avec la touche Echap
window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        if (modalOverlay && !modalOverlay.classList.contains('hidden')) closeModal(modalOverlay);
        if (modalOverlay2 && !modalOverlay2.classList.contains('hidden')) closeModal(modalOverlay2);
        if (modalOverlayDelete && !modalOverlayDelete.classList.contains('hidden')) closeModal(modalOverlayDelete);
    }
});

/* Note : Nous n'ajoutons pas d'écouteur sur 'modalOverlay' 
    pour empêcher la fermeture au clic à l'extérieur.
*/