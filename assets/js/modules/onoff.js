function submitToggle(checkbox) {
  const id = checkbox.dataset.es;
  const sessionId = checkbox.dataset.ss;

  const url = `/api/enseignement_state_view/${id}?ssd=${sessionId}`;

  fetch(url, {
    method: "GET",
    headers: {
      "X-Requested-With": "XMLHttpRequest",
      Accept: "application/json",
    },
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error(`Erreur HTTP ${response.status}`);
      }
      // On récupère d'abord en texte pour pouvoir diagnostiquer si c'est du HTML
      return response.text();
    })
    .then((text) => {
      try {
        const data = JSON.parse(text);
        if (data.status === "success") {
          refreshCards();
        } else {
          alert("Erreur : " + (data.message || "Action impossible"));
          checkbox.checked = !checkbox.checked;
        }
      } catch (parseError) {
        // C'EST ICI QUE VOUS VERREZ L'ERREUR PHP
        console.error("Le serveur a renvoyé du HTML au lieu de JSON !");
        console.error("Contenu reçu :", text);

        // On affiche une partie de l'erreur PHP pour aider au debug
        const debugMatch = text.match(/<b>(.*?)<\/b>/g);
        const debugMsg = debugMatch
          ? debugMatch.join(" ")
          : "Erreur interne du serveur";

        alert(
          "Erreur Serveur (PHP) détectée : \n" +
            debugMsg.replace(/<[^>]*>?/gm, ""),
        );
        checkbox.checked = !checkbox.checked;
      }
    })
    .catch((error) => {
      console.error("Erreur de communication :", error);
      alert("Impossible de joindre le serveur.");
      checkbox.checked = !checkbox.checked;
    });
}

function submitToggle2(checkbox) {
  const id = checkbox.dataset.id;
  const sessionId = checkbox.dataset.ss;

  const url = `/api/enseignement_state_view_2/${id}?ssd=${sessionId}`;

  console.log(id, sessionId, url);

  fetch(url, {
    method: "GET",
    headers: {
      "X-Requested-With": "XMLHttpRequest",
      Accept: "application/json",
    },
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error(`Erreur HTTP ${response.status}`);
      }
      // On récupère d'abord en texte pour pouvoir diagnostiquer si c'est du HTML
      return response.text();
    })
    .then((text) => {
      try {
        const data = JSON.parse(text);
        if (data.status === "success") {
          refreshCards();
        } else {
          alert("Erreur : " + (data.message || "Action impossible"));
          checkbox.checked = !checkbox.checked;
        }
      } catch (parseError) {
        // C'EST ICI QUE VOUS VERREZ L'ERREUR PHP
        console.error("Le serveur a renvoyé du HTML au lieu de JSON !");
        console.error("Contenu reçu :", text);

        // On affiche une partie de l'erreur PHP pour aider au debug
        const debugMatch = text.match(/<b>(.*?)<\/b>/g);
        const debugMsg = debugMatch
          ? debugMatch.join(" ")
          : "Erreur interne du serveur";

        alert(
          "Erreur Serveur (PHP) détectée : \n" +
            debugMsg.replace(/<[^>]*>?/gm, ""),
        );
        checkbox.checked = !checkbox.checked;
      }
    })
    .catch((error) => {
      console.error("Erreur de communication :", error);
      alert("Impossible de joindre le serveur.");
      checkbox.checked = !checkbox.checked;
    });
}

function refreshCards() {
  const cardsContainer = document.getElementById("cards");
  if (!cardsContainer) return;

  cardsContainer.style.opacity = "0.5"; // Feedback visuel

  fetch(window.location.href)
    .then((response) => response.text())
    .then((html) => {
      const parser = new DOMParser();
      const doc = parser.parseFromString(html, "text/html");
      const newContent = doc.getElementById("cards");

      if (newContent) {
        cardsContainer.innerHTML = newContent.innerHTML;
      }
      cardsContainer.style.opacity = "1";
    })
    .catch((err) => {
      console.error("Erreur lors du rafraîchissement :", err);
      cardsContainer.style.opacity = "1";
    });
}
