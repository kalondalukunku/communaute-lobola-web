function submitToggle(checkbox) {
    const id = checkbox.dataset.es;
    
    const url = `/api/enseignement_state_view/${id}`;

    fetch(url)
    .then(response => {
        if (!response.ok) {
            // Si 404, on affiche l'URL pour déboguer
            throw new Error(`Erreur ${response.status} sur l'URL : ${url}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            refreshCards();
        } else {
            alert("Erreur : " + data.message);
            checkbox.checked = !checkbox.checked;
        }
    })
    .catch(error => {
        console.error("Erreur Fetch :", error);
        alert("Erreur de communication avec le serveur.");
        checkbox.checked = !checkbox.checked;
    });
}

function refreshCards() {
    const cardsContainer = document.getElementById('cards');
    if (!cardsContainer) return;

    // Ajout d'un effet visuel de chargement (opacité)

    fetch(window.location.href)
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContent = doc.getElementById('cards');

            if (newContent) {
                cardsContainer.innerHTML = newContent.innerHTML;
            }
        })
        .catch(err => {
            console.error("Erreur lors du rafraîchissement :", err);
        });
}