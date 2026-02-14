document.addEventListener('DOMContentLoaded', () => {
    const audio = document.getElementById('main-player');
    const playBtn = document.getElementById('play-btn');
    const playIcon = document.getElementById('play-icon');
    const trackItems = document.querySelectorAll('.track-item');
    const seekBar = document.getElementById('seek-bar');
    const timeNow = document.getElementById('time-now');
    const timeTotal = document.getElementById('time-total');
    const volumeSlider = document.getElementById('volume-slider');
    const volumeIcon = document.getElementById('volume-icon');
    const muteBtn = document.getElementById('mute-btn');
    
    let currentIndex = 0;
    playBtn.innerHTML  = '<i id="play-icon" class="fa-solid fa-play text-xl ml-1"></i>';
    // Appliquer le volume initial
    audio.volume = volumeSlider.value;

    // Écouteur sur le slider
    volumeSlider.addEventListener('input', (e) => {
        const val = e.target.value;
        audio.volume = val;
        updateVolumeIcon(val);
    });

    // Fonction pour mettre à jour l'icône selon le niveau
    function updateVolumeIcon(val) {
        volumeIcon.className = 'fa-solid text-gray-500 text-xs transition-colors group-hover:text-primary ';
        if (val == 0) {
            volumeIcon.classList.add('fa-volume-xmark');
        } else if (val < 0.5) {
            volumeIcon.classList.add('fa-volume-low');
        } else {
            volumeIcon.classList.add('fa-volume-high');
        }
    }

    // Optionnel : Toggle Mute au clic sur l'icône
    let lastVolume = 0.8;
    muteBtn.addEventListener('click', () => {
        if (audio.volume > 0) {
            lastVolume = audio.volume;
            audio.volume = 0;
            volumeSlider.value = 0;
        } else {
            audio.volume = lastVolume;
            volumeSlider.value = lastVolume;
        }
        updateVolumeIcon(audio.volume);
    });
    // Récupérer les données depuis le HTML (les fameux data-attributes)
    const getTrackData = (element) => ({
        url: element.getAttribute('data-url'),
        es: element.getAttribute('data-es'),
        sr: element.getAttribute('data-sr'),
        title: element.getAttribute('data-title'),
        desc: element.getAttribute('data-desc'),
        index: parseInt(element.getAttribute('data-index'))
    });

    // Fonction pour charger et lire une piste
    const playTrack = (element) => {
        const data = getTrackData(element);        

        if (data.es) {
            fetch(`/lobola/enseignement/add_view/${data.es}?sr=${data.sr}`)
                .then(response => {
                    console.log(response);
                    
                    if (!response.ok) console.warn("Erreur lors de l'ajout de la vue");
                })
                .catch(error => console.error("Erreur réseau pour les statistiques:", error));
        }
        
        // Mise à jour visuelle
        trackItems.forEach(item => item.classList.remove('active'));
        element.classList.add('active');
        
        document.getElementById('current-title').textContent = data.title;
        document.getElementById('current-desc').textContent = data.desc;
        
        // Chargement audio
        audio.src = data.url;
        audio.play();
        playBtn.innerHTML  = '<i id="play-icon" class="fa-solid fa-pause text-xl"></i>';
        currentIndex = data.index;
    };

    // Events sur les items de la liste
    trackItems.forEach(item => {
        item.addEventListener('click', () => playTrack(item));
    });

    // Play/Pause
    playBtn.addEventListener('click', () => {
        if (!audio.src) {
            // Si rien n'est chargé, on joue le premier
            if (trackItems.length > 0) playTrack(trackItems[0]);
            return;
        }

        if (audio.paused) {
            audio.play();
            playBtn.innerHTML  = '';
            playBtn.innerHTML  = '<i id="play-icon" class="fa-solid fa-pause text-xl"></i>';
        } else {
            audio.pause();
            playBtn.innerHTML  = '';
            playBtn.innerHTML  = '<i id="play-icon" class="fa-solid fa-play text-xl ml-1"></i>';
        }
    });

    // Prochain / Précédent
    document.getElementById('next-btn').addEventListener('click', () => {
        const next = currentIndex + 1;
        if (next < trackItems.length) playTrack(trackItems[next]);
    });

    document.getElementById('prev-btn').addEventListener('click', () => {
        const prev = currentIndex - 1;
        if (prev >= 0) playTrack(trackItems[prev]);
    });

    // Mise à jour de la barre de progression
    audio.addEventListener('timeupdate', () => {
        const progress = (audio.currentTime / audio.duration) * 100;
        seekBar.value = progress || 0;
        
        timeNow.textContent = formatTime(audio.currentTime);
        if (!isNaN(audio.duration)) {
            timeTotal.textContent = formatTime(audio.duration);
        }
    });

    seekBar.addEventListener('input', () => {
        const time = (seekBar.value / 100) * audio.duration;
        audio.currentTime = time;
    });

    function formatTime(seconds) {
        const min = Math.floor(seconds / 60);
        const sec = Math.floor(seconds % 60);
        return `${min.toString().padStart(2, '0')}:${sec.toString().padStart(2, '0')}`;
    }

    // Auto-next à la fin
    audio.addEventListener('ended', () => {
        const next = currentIndex + 1;
        if (next < trackItems.length) playTrack(trackItems[next]);
        else playIcon.innerHTML  = '<i id="play-icon" class="fa-solid fa-play text-xl ml-1"></i>';
    });
});