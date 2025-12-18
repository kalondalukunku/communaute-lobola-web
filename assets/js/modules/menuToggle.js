
// Logique pour le menu déroulant mobile (les liens principaux)
const mobileMenuToggle = document.getElementById('mobileMenuToggle');
// Création d'un menu simple pour les petits écrans
const mobileNavContainer = document.createElement('div');
mobileNavContainer.className = 'fixed top-16 inset-x-0 bg-blue-900/95 shadow-xl transition-all duration-300 transform -translate-y-full z-30 lg:hidden';
mobileNavContainer.id = 'mobileNavContainer';

const navLinks = document.querySelector('nav.flex.space-x-1').cloneNode(true);
navLinks.className = 'flex flex-col p-4 space-x-0 space-y-2';
navLinks.querySelectorAll('a').forEach(link => {
        // Supprime la classe whitespace-nowrap et assure la largeur complète
    link.classList.remove('whitespace-nowrap', 'hover:scale-[1.02]');
    link.classList.add('w-full');
});

mobileNavContainer.appendChild(navLinks);
document.body.appendChild(mobileNavContainer);


mobileMenuToggle.addEventListener('click', () => {
    if (mobileNavContainer.classList.contains('-translate-y-full')) {
        mobileNavContainer.classList.remove('-translate-y-full');
        mobileNavContainer.classList.add('translate-y-0');
    } else {
        mobileNavContainer.classList.add('-translate-y-full');
        mobileNavContainer.classList.remove('translate-y-0');
    }
});

function logout() {
    // Logique de déconnexion
    console.log("Déconnexion de l'utilisateur. Redirection vers la page de login...");
    // window.location.href = '/login'; 
}