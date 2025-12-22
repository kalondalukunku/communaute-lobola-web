
/**
 * Script de suivi de la soumission de formulaire et de l'état de chargement de la page.
 *
 * Ce script utilise sessionStorage pour stocker un indicateur de soumission avant
 * le rechargement de la page.
 */

const SUBMIT_FLAG_KEY = 'formSubmitted';
/**
 * Logique JavaScript pour les interactions utilisateur, la fluidité et les micro-animations.
 */

const loginForm = document.getElementById('loginForm');
const loginButton = document.getElementById('loginButton');
const buttonText = document.getElementById('buttonText');
const loadingSpinner = document.getElementById('loadingSpinner');
const notificationArea = document.getElementById('notificationArea');
const passwordInput = document.getElementById('password');
const togglePasswordButton = document.getElementById('togglePassword');
const eyeOpenIcon = document.getElementById('eyeOpen');
const eyeClosedIcon = document.getElementById('eyeClosed');


// --- Fonctions d'Utilitaires de l'Interface Utilisateur ---

/**
 * Affiche une notification temporaire.
 * @param {string} message - Le message à afficher.
 * @param {string} type - 'success', 'error', 'info'.
 */
function showNotification(message, type) {
	const colorMap = {
		// Couleurs de notification adaptées au thème Orange/Bleu
		success: 'bg-green-100 border-green-500 text-green-800',
		error: 'bg-red-100 border-red-500 text-red-800',
		info: 'bg-blue-100 border-blue-500 text-blue-800',
	};

	const notification = document.createElement('div');
	notification.className = `p-4 border-l-4 rounded-lg shadow-lg max-w-xs ${colorMap[type]} transform transition-all duration-300 translate-x-full opacity-0`;
	notification.innerHTML = `
		<p class="font-semibold">${type === 'error' ? 'Erreur de connexion' : 'Information'}</p>
		<p class="text-xs">${message}</p>
	`;

	notificationArea.appendChild(notification);

	// Animer l'entrée
	setTimeout(() => {
		notification.style.transform = 'translateX(0)';
		notification.style.opacity = '1';
	}, 50);

	// Animer la sortie et retirer après 5 secondes
	setTimeout(() => {
		notification.style.transform = 'translateX(100%)';
		notification.style.opacity = '0';
		notification.addEventListener('transitionend', () => {
			notification.remove();
		});
	}, 5000);
}

/**
 * Gère l'état du bouton pendant la soumission du formulaire.
 * @param {boolean} isLoading - True pour activer l'état de chargement, False sinon.
 */
function setButtonLoading(isLoading) {
	if (isLoading) {
		loginButton.disabled = true;
		loginButton.classList.add('btn-loading');
		buttonText.textContent = '';
		loadingSpinner.classList.remove('hidden');
		loadingSpinner.classList.add('inline-block');
	} else {
		loginButton.disabled = false;
		loginButton.classList.remove('btn-loading');
		buttonText.textContent = 'Se connecter';
		loadingSpinner.classList.add('hidden');
		loadingSpinner.classList.remove('inline-block');
	}
}

/**
 * Affiche une modale
 */
function showModal(id) {
	const modal = document.getElementById(id);
	if (modal) {
		modal.classList.remove('hidden');
		modal.style.opacity = '1';
		// La modale utilise un transform: scale pour l'animation
		setTimeout(() => {
			modal.querySelector('.bg-white').style.transform = 'scale(1)';
		}, 10);
	}
}

/**
 * Cache une modale
 */
function hideModal(id) {
	const modal = document.getElementById(id);
	if (modal) {
		modal.style.opacity = '0';
		modal.querySelector('.bg-white').style.transform = 'scale(0.95)';
		setTimeout(() => {
			modal.classList.add('hidden');
		}, 300); // 300ms correspond à la durée de transition CSS
	}
}

/**
 * Logique pour le bouton Afficher/Masquer le mot de passe.
 */
function togglePasswordVisibility() {
	const isPassword = passwordInput.type === 'password';
	passwordInput.type = isPassword ? 'text' : 'password';
	
	eyeOpenIcon.classList.toggle('hidden', !isPassword);
	eyeClosedIcon.classList.toggle('hidden', isPassword);
}

function simulateAction(action) {
	let message;
	if (action === 'télécharger') {
		message = "Simulation : Le fichier 'Contrat_CDI_JDupont_2018.pdf' est en cours de téléchargement.";
	} else if (action === 'supprimer') {
		message = "Simulation : Une fenêtre modale de confirmation s'ouvrirait avant la suppression.";
	} else if (action === 'modifier_meta') {
		message = "Simulation : Ouverture d'un formulaire pour modifier les métadonnées.";
	}
	// Utilisation d'une simple alerte pour la démo, à remplacer par une modale customisée
	alert(message);
}

// --- Logique de Connexion Simulé ---

/**
 * Gère la soumission du formulaire (simulée).
 * @param {Event} event - L'événement de soumission.
 */
// async function handleLogin(event) {
// 	event.preventDefault(); // Empêche le rechargement de la page
// 	setButtonLoading(true);

// 	// Récupérer les valeurs (pour la simulation)
// 	const email = document.getElementById('email').value;
// 	const password = document.getElementById('password').value;

// 	// Simuler un appel API de 2 secondes
// 	await new Promise(resolve => setTimeout(resolve, 2000));

// 	// Logique de validation simple
// 	if (email === 'admin@gouv.fr' && password === 'P@ssw0rd123') {
// 		showNotification("Connexion réussie ! Redirection...", 'success');
// 		// Simuler la redirection après un court délai
// 		setTimeout(() => {
// 			console.log("Redirection vers le tableau de bord...");
// 		}, 1000);
// 	} else if (email === 'erreur@gouv.fr') {
// 		showNotification("Le compte utilisateur est désactivé. Veuillez contacter l'assistance.", 'error');
// 	} else {
// 		showNotification("Nom d'utilisateur ou mot de passe incorrect. Veuillez réessayer.", 'error');
// 	}

// 	setButtonLoading(false);
// }

/**
 * 1. Détecte la soumission d'un formulaire et enregistre l'indicateur.
 * Cette fonction est appelée AVANT que le navigateur ne navigue vers la nouvelle page.
 */
// function trackFormSubmission() {
//     // Écoute tous les événements de soumission de formulaire sur la page
//     document.querySelectorAll('form').forEach(form => {
//         form.addEventListener('submit', () => {
//             // Définit l'indicateur dans sessionStorage. 
//             // Ce stockage persiste pendant le rechargement de la page.
// 			setButtonLoading(true);
//             sessionStorage.setItem(SUBMIT_FLAG_KEY, 'true');
//         });
//     });
// }

/**
 * 2. Vérifie l'indicateur de soumission au chargement de la nouvelle page.
 * Cette fonction est appelée lorsque le DOM est complètement chargé.
 */
// function handlePageLoadCompletion() {
//     const wasSubmitted = sessionStorage.getItem(SUBMIT_FLAG_KEY);

//     if (wasSubmitted === 'true') {
//         // La page est terminée de charger et une soumission de formulaire précédente a été faite.
        
//         // --- Signal de fin de chargement ---
// 		setButtonLoading(false);
        
//         // Réinitialise l'indicateur pour la prochaine session/soumission
//         sessionStorage.removeItem(SUBMIT_FLAG_KEY);

//     }
// }

// --- Initialisation des écouteurs ---

// Démarre le suivi des soumissions dès que possible
// trackFormSubmission();

// Gère la fin du chargement de la nouvelle page
// document.addEventListener('DOMContentLoaded', handlePageLoadCompletion);




// --- Fonctions de gestion des Modales ---

// Modale Métadonnées
function showMetadataModal() {
	document.getElementById('metadata-modal').style.display = 'flex';
}

function hideMetadataModal() {
	document.getElementById('metadata-modal').style.display = 'none';
}

function saveMetadataChanges() {
	console.log("Simulation : Les métadonnées ont été enregistrées.");
	alert("Les modifications de métadonnées ont été enregistrées (Simulation).");
	hideMetadataModal();
}

// Modale Suppression
function showDeleteModal() {
	document.getElementById('delete-modal').style.display = 'flex';
}

function hideDeleteModal() {
	document.getElementById('delete-modal').style.display = 'none';
}

function confirmDelete() {
	console.log("Simulation : Document supprimé.");
	alert("Le document a été supprimé (Simulation). Redirection vers la liste...");
	hideDeleteModal();
	// window.location.href = 'gestion_documents.html'; // Redirection réelle
}

// document.addEventListener('DOMContentLoaded', () => {
// 	const params = new URLSearchParams(window.location.search);
// 	const docId = params.get('docId') || 'Inconnu';
// 	console.log(`Document ID chargé: ${docId}`);
// });

// // Fermer les modales en cliquant à l'extérieur
// document.getElementById('metadata-modal').addEventListener('click', (e) => {
// 	if (e.target === document.getElementById('metadata-modal')) {
// 		hideMetadataModal();
// 	}
// });
// document.getElementById('delete-modal').addEventListener('click', (e) => {
// 	if (e.target === document.getElementById('delete-modal')) {
// 		hideDeleteModal();
// 	}
// });

const overlay = document.getElementById('modalOverlay');
const content = document.getElementById('modalContent');

function openModal() {
	overlay.classList.remove('hidden');
	setTimeout(() => {
		overlay.classList.add('opacity-100');
		content.classList.remove('scale-90');
		content.classList.add('scale-100');
	}, 10);
}

function closeModal() {
	overlay.classList.remove('opacity-100');
	content.classList.remove('scale-100');
	content.classList.add('scale-90');
	setTimeout(() => {
		overlay.classList.add('hidden');
	}, 300);
}



const menuButton = document.getElementById('menu-button');
const dropdownMenu = document.getElementById('dropdown-menu');
const chevronIcon = document.getElementById('chevron-icon');

// Fonction pour basculer le menu
function toggleMenu() {
	const isExpanded = menuButton.getAttribute('aria-expanded') === 'true';
	
	if (isExpanded) {
		closeMenu();
	} else {
		openMenu();
	}
}

function openMenu() {
	menuButton.setAttribute('aria-expanded', 'true');
	dropdownMenu.classList.add('show');
	chevronIcon.classList.add('rotate-180');
}

function closeMenu() {
	menuButton.setAttribute('aria-expanded', 'false');
	dropdownMenu.classList.remove('show');
	chevronIcon.classList.remove('rotate-180');
}

// Listener sur le bouton
menuButton.addEventListener('click', (e) => {
	e.stopPropagation();
	toggleMenu();
});

// Fermer si on clique ailleurs dans le document
document.addEventListener('click', (event) => {
	if (!dropdownMenu.contains(event.target)) {
		closeMenu();
	}
});

// Fermer avec la touche Échap
document.addEventListener('keydown', (event) => {
	if (event.key === 'Escape') {
		closeMenu();
	}
});

// Fermer si on clique n'importe où en dehors du conteneur
window.addEventListener('click', (e) => {
	const container = document.getElementById('dropdownContainer');
	if (!container.contains(e.target)) {
		closeDropdown();
	}
});



// Pour gérer les liens de navigation (simulés ici car les fichiers ne sont pas créés)
// Vous pouvez retirer cette fonction si vous liez réellement les pages.
document.querySelectorAll('a').forEach(link => {
	link.addEventListener('click', function(e) {
		// Empêche la navigation réelle si l'attribut href est un nom de fichier non existant
		if (this.href.includes('.html')) {
			// Pour cet exemple, on peut laisser la navigation simuler si la page est là
		}
	});
});



// Logique de gestion des onglets
document.addEventListener('DOMContentLoaded', function () {
	// Sélection des boutons et contenus avec les NOUVELLES classes (js-tab-...)
	const tabs = document.querySelectorAll('.js-tab-button');
	const contents = document.querySelectorAll('.js-tab-content');

	function showTab(tabId) {
		// 1. Désactiver tous les boutons d'onglet et masquer tous les contenus
		tabs.forEach(tab => tab.classList.remove('active'));
		contents.forEach(content => content.classList.add('hidden'));

		// 2. Trouver l'onglet actif et son contenu
		const activeButton = document.querySelector(`.js-tab-button[data-tab="${tabId}"]`);
		const activeContent = document.getElementById(tabId);

		// 3. Appliquer les classes pour afficher
		if (activeButton) {
			activeButton.classList.add('active');
		}
		if (activeContent) {
			activeContent.classList.remove('hidden');
		}
	}

	// Événement de clic sur les boutons d'onglet
	tabs.forEach(tab => {
		tab.addEventListener('click', function () {
			showTab(this.dataset.tab);
		});
	});

	// Initialisation : Afficher l'onglet actif au chargement
	if (tabs.length > 0) {
		const initialActiveTab = document.querySelector('.js-tab-button.active');
		if (initialActiveTab) {
			// Si un onglet est déjà actif (grâce au HTML), on s'assure que son contenu est visible.
			showTab(initialActiveTab.dataset.tab);
		} else {
			// Sinon, on active le premier onglet par défaut.
			showTab(tabs[0].dataset.tab);
		}
	}
});


// --- Initialisation/Événements ---

document.addEventListener('DOMContentLoaded', () => {
	// 1. Animation des champs lors du focus
	document.querySelectorAll('.relative input').forEach(input => {
		input.addEventListener('focus', () => {
			input.parentElement.classList.add('scale-[1.01]', 'shadow-md', 'transition', 'duration-150');
		});
		input.addEventListener('blur', () => {
			input.parentElement.classList.remove('scale-[1.01]', 'shadow-md', 'transition', 'duration-150');
		});
	});
	
	// 2. Visibilité du mot de passe
	if (togglePasswordButton) {
		togglePasswordButton.addEventListener('click', togglePasswordVisibility);
	}
});


// Logique de gestion des onglets
document.addEventListener('DOMContentLoaded', () => {
	const tabs = document.querySelectorAll('.tab-button');
	const contents = document.querySelectorAll('.tab-content');

	tabs.forEach(tab => {
		tab.addEventListener('click', () => {
			const targetId = tab.getAttribute('data-tab');
			const targetContent = document.getElementById(targetId);
			const activeContent = document.querySelector('.tab-content.active');
			
			// 1. Désactiver visuellement tous les onglets
			tabs.forEach(t => t.classList.remove('active-tab', 'text-[var(--color-secondary)]'));
			tabs.forEach(t => t.classList.add('text-gray-500', 'hover:text-[var(--color-secondary)]'));

			// 2. Activer visuellement l'onglet cliqué
			tab.classList.add('active-tab', 'text-[var(--color-secondary)]');
			tab.classList.remove('text-gray-500', 'hover:text-[var(--color-secondary)]');

			// --- Logique de Transition (Fondu enchaîné) ---
			
			if (activeContent) {
				// 3. Lancer le fondu OUT (disparition) du contenu ACTIF
				activeContent.style.opacity = '0';
				
				// 4. Attendre la fin du fondu OUT pour changer le display et lancer le fondu IN
				// La durée de transition est de 300ms (définie en CSS)
				setTimeout(() => {
					// Masquer l'ancien contenu après la transition
					activeContent.classList.remove('active');
					
					// Afficher le nouveau contenu (display: block)
					targetContent.classList.add('active');
					
					// Lancer le fondu IN (apparition) du NOUVEAU contenu
					// C'est le style inline qui prend le dessus sur l'opacité 0 du CSS
					setTimeout(() => {
						targetContent.style.opacity = '1';
					}, 10); // Petit délai pour garantir que le navigateur voit le changement de display
					
				}, 300); // 300ms correspond à la durée de transition CSS
			} else {
				// Cas initial : Afficher directement le premier contenu sans fondu OUT
				targetContent.classList.add('active');
				targetContent.style.opacity = '1';
			}
		});
	});
	
	// Logique de déconnexion (simple placeholder)
	window.logout = function() {
		console.log("Déconnexion de l'utilisateur. Redirection vers la page de login...");
		// window.location.href = '/login'; 
	}
	
	// Initialisation de l'opacité au chargement (pour le premier onglet actif)
	const initialActiveContent = document.querySelector('.tab-content.active');
	if (initialActiveContent) {
			initialActiveContent.style.opacity = '1';
	}
});