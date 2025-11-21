// GESTION DU TIME AGO
    function timeAgo(date) {
        const now = new Date();
        const past = new Date(date);
        const seconds = Math.floor((now - past) / 1000);

        const intervals = {
            year: 31536000,
            month: 2592000,
            week: 604800,
            day: 86400,
            hour: 3600,
            minute: 60,
            second: 1
        };

        for (const unit in intervals) {
            const value = Math.floor(seconds / intervals[unit]);
            if (value >= 1) {
                const label = {
                    year: "an",
                    month: "mois",
                    week: "semaine",
                    day: "jour",
                    hour: "heure",
                    minute: "minute",
                    second: "seconde"
                }[unit];

                return `il y a ${value} ${label}${value > 1 && unit !== "month" ? "s" : ""}`;
            }
        }

        return "à l’instant";
    }

    // 🔁 Mise à jour automatique de tous les éléments .time_ago
    function updateTimeAgo() {
        document.querySelectorAll(".time_ago").forEach(el => {
            const date = el.dataset.timeAgo; // récupère data-time-ago
            el.textContent = timeAgo(date);
        });
    }

    // ⏱ Mettre à jour au chargement + toutes les 30 secondes
    updateTimeAgo();
    setInterval(updateTimeAgo, 1000);

