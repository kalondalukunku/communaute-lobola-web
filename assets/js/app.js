document.addEventListener("DOMContentLoaded", function() {
	const loader = document.getElementById('spiritual-loader');
	const quote = document.getElementById('loader-quote');
	const quotes = [
		"La Maât guide vos pas",
		"La lumière est en vous",
		"Écoutez la voie de vos ancêtres",
		"Devenez des maîtres de votre destin",
		"Honorez la Terre et les Cieux de vos ancêtres",
		"Que la sagesse de Maât vous accompagne",
		"Ensemble, nous sommes plus forts",
		"Que la paix soit avec vous",
	];

	// Afficher une citation aléatoire
	quote.innerText = quotes[Math.floor(Math.random() * quotes.length)];
	quote.style.opacity = "1";

	// Masquer le loader une fois que tout est chargé
	window.addEventListener("load", function() {
		setTimeout(() => {
			loader.style.opacity = "0";
			document.body.classList.remove('loading');
			setTimeout(() => {
				loader.style.display = "none";
			}, 700);
		}, 1000); // Délai minimum de 1s pour laisser l'âme du site respirer
	});
});

// Ajouter la classe au démarrage
document.body.classList.add('loading');


function copyToClipboard(text) {
	const el = document.createElement('textarea');
	el.value = text;
	document.body.appendChild(el);
	el.select();
	document.execCommand('copy');
	document.body.removeChild(el);
	
	// Notification visuelle simple
	const btn = event.currentTarget;
	const originalText = btn.innerHTML;
	btn.innerHTML = '<i class="fas fa-check"></i> Copié !';
	setTimeout(() => {
		btn.innerHTML = originalText;
	}, 2000);
}

const sidebar = document.getElementById('sidebar');
const openBtn = document.getElementById('openSidebar');
const closeBtn = document.getElementById('closeSidebar');
const overlay = document.getElementById('overlay');

function toggleMenu() {
	const isActive = sidebar.classList.contains('mobile-active');
	
	if (!isActive) {
		overlay.classList.remove('hidden');
		setTimeout(() => overlay.classList.add('opacity-100'), 10);
		sidebar.classList.add('mobile-active');
		document.body.style.overflow = 'hidden';
	} else {
		overlay.classList.remove('opacity-100');
		sidebar.style.transform = 'translateY(-10px)';
		sidebar.style.opacity = '0';
		
		setTimeout(() => {
			sidebar.classList.remove('mobile-active');
			sidebar.style.transform = ''; // Reset styles
			sidebar.style.opacity = '';
			overlay.classList.add('hidden');
			document.body.style.overflow = '';
		}, 300);
	}
}

openBtn.addEventListener('click', toggleMenu);
closeBtn.addEventListener('click', toggleMenu);
overlay.addEventListener('click', toggleMenu);


