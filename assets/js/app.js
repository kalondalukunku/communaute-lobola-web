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