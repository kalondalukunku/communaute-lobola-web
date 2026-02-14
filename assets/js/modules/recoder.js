const audioInput = document.getElementById('audioFile');
const dropZone = document.getElementById('dropZone');
const uploadUI = document.getElementById('uploadUI');
const successUI = document.getElementById('successUI');
const fileNameDisplay = document.getElementById('fileName');
const fileSizeDisplay = document.getElementById('fileSize');
const audioPreview = document.getElementById('audioPreview');
const removeFile = document.getElementById('removeFile');
const dureeInput = document.getElementById('duree'); // Votre input hidden

// Styles au survol
['dragenter', 'dragover'].forEach(eventName => {
    dropZone.addEventListener(eventName, e => {
        e.preventDefault();
        if(!audioInput.files.length) dropZone.classList.add('drop-zone--over');
    });
});

['dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, e => {
        e.preventDefault();
        dropZone.classList.remove('drop-zone--over');
    });
});

// ÉCOUTEUR CLÉ : Récupère la durée quand le fichier est chargé dans le lecteur
audioPreview.addEventListener('loadedmetadata', function() {
    if (audioPreview.duration) {
        // On arrondit la durée (ex: 215 secondes)
        const totalSeconds = Math.round(audioPreview.duration);
        dureeInput.value = totalSeconds;
        console.log("Durée capturée : " + totalSeconds + " secondes");
    }
});

// Gestion du changement de fichier
audioInput.addEventListener('change', function() {
    if (this.files && this.files[0]) {
        const file = this.files[0];
        
        // Vérification si c'est bien de l'audio
        if (!file.type.startsWith('audio/')) {
            // Remplacer alert par une div de message si possible selon vos règles
            console.error("Fichier non valide");
            this.value = "";
            return;
        }

        // Mise à jour des infos
        fileNameDisplay.textContent = file.name;
        fileSizeDisplay.textContent = (file.size / (1024 * 1024)).toFixed(2) + " Mo";
        
        // Préparation du lecteur
        const reader = new FileReader();
        reader.onload = function(e) {
            audioPreview.src = e.target.result;
            // La durée sera mise à jour via l'événement 'loadedmetadata' ci-dessus
        }
        reader.readAsDataURL(file);

        // Changement d'état visuel
        uploadUI.classList.add('hidden');
        successUI.classList.remove('hidden');
        successUI.classList.add('flex');
        dropZone.classList.add('drop-zone--success');
        dropZone.classList.remove('border-dashed');
    }
});

// Bouton reset
removeFile.addEventListener('click', (e) => {
    e.preventDefault();
    e.stopPropagation();
    
    audioInput.value = "";
    audioPreview.src = "";
    dureeInput.value = ""; // On vide aussi la durée
    
    uploadUI.classList.remove('hidden');
    successUI.classList.add('hidden');
    successUI.classList.remove('flex');
    dropZone.classList.remove('drop-zone--success');
    dropZone.classList.add('border-dashed');
});