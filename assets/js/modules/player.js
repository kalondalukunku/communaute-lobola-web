document.addEventListener('DOMContentLoaded', () => {
        const audio = document.getElementById('audioSource');
        const btnPlayPause = document.getElementById('btnPlayPause');
        const iconPlayPause = document.getElementById('playPauseIcon');
        const progressContainer = document.getElementById('progressBarContainer');
        const progressFill = document.getElementById('progressBarFill');
        const timeCurrent = document.getElementById('timeCurrent');
        const timeDuration = document.getElementById('timeDuration');
        const playbackPercent = document.getElementById('playbackPercent');
        const visualizer = document.getElementById('audioVisualizer');
        const btnRewind = document.getElementById('btnRewind');
        const btnForward = document.getElementById('btnForward');

        // Formater les secondes en MM:SS
        function formatTime(seconds) {
            const min = Math.floor(seconds / 60);
            const sec = Math.floor(seconds % 60);
            return `${min.toString().padStart(2, '0')}:${sec.toString().padStart(2, '0')}`;
        }

        // Toggle Lecture / Pause
        btnPlayPause.addEventListener('click', () => {
            if (audio.paused) {
                audio.play();
                iconPlayPause.classList.replace('fa-play', 'fa-pause');
                iconPlayPause.classList.remove('ml-1');
                visualizer.classList.add('animate-pulse-custom');
            } else {
                audio.pause();
                iconPlayPause.classList.replace('fa-pause', 'fa-play');
                iconPlayPause.classList.add('ml-1');
                visualizer.classList.remove('animate-pulse-custom');
            }
        });

        // Mise à jour de la progression
        audio.addEventListener('timeupdate', () => {
            if (!isNaN(audio.duration)) {
                const percent = (audio.currentTime / audio.duration) * 100;
                progressFill.style.width = `${percent}%`;
                playbackPercent.innerText = `${Math.round(percent)}% écouté`;
                timeCurrent.innerText = formatTime(audio.currentTime);
            }
        });

        // Charger la durée une fois le fichier prêt
        audio.addEventListener('loadedmetadata', () => {
            timeDuration.innerText = formatTime(audio.duration);
        });

        // Sauter à un moment précis via la barre
        progressContainer.addEventListener('click', (e) => {
            const width = progressContainer.clientWidth;
            const clickX = e.offsetX;
            const duration = audio.duration;
            if (!isNaN(duration)) {
                audio.currentTime = (clickX / width) * duration;
            }
        });

        // Boutons -5s et +5s
        btnRewind.addEventListener('click', () => { audio.currentTime = Math.max(0, audio.currentTime - 5); });
        btnForward.addEventListener('click', () => { audio.currentTime = Math.min(audio.duration, audio.currentTime + 5); });

        // Reset à la fin
        audio.addEventListener('ended', () => {
            iconPlayPause.classList.replace('fa-pause', 'fa-play');
            iconPlayPause.classList.add('ml-1');
            visualizer.classList.remove('animate-pulse-custom');
            progressFill.style.width = '0%';
        });
    });