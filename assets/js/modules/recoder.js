// let mediaRecorder;
//         let audioChunks = [];
//         const recordBtn = document.getElementById('recordBtn');
//         const micIcon = document.getElementById('micIcon');
//         const statusText = document.getElementById('recordingStatus');
//         const audioPreview = document.getElementById('audioPreviewContainer');
//         const audioPlayback = document.getElementById('audioPlayback');
//         const teacherForm = document.getElementById('teacherForm');
//         const deleteAudio = document.getElementById('deleteAudio');
//         const realAudioInput = document.getElementById('realAudioInput');
        
//         let isRecording = false;

//         recordBtn.addEventListener('click', async () => {
//             if (!isRecording) {
//                 try {
//                     const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
//                     // On force un mimeType compatible si possible
//                     const options = { mimeType: MediaRecorder.isTypeSupported('audio/webm') ? 'audio/webm' : 'audio/ogg' };
//                     mediaRecorder = new MediaRecorder(stream, options);
//                     audioChunks = [];

//                     mediaRecorder.ondataavailable = (e) => {
//                         if (e.data.size > 0) audioChunks.push(e.data);
//                     };

//                     mediaRecorder.onstop = () => {
//                         const audioBlob = new Blob(audioChunks, { type: mediaRecorder.mimeType });
//                         const audioUrl = URL.createObjectURL(audioBlob);
//                         audioPlayback.src = audioUrl;

//                         // TECHNIQUE DU DATATRANSFER : 
//                         // On crée un objet File à partir du Blob et on l'injecte dans l'input file
//                         const file = new File([audioBlob], "enseignement_audio.webm", { 
//                             type: mediaRecorder.mimeType, 
//                             lastModified: new Date().getTime() 
//                         });

//                         const dataTransfer = new DataTransfer();
//                         dataTransfer.items.add(file);
//                         realAudioInput.files = dataTransfer.files;

//                         console.log("Fichier audio injecté dans l'input:", realAudioInput.files[0]);

//                         // UI
//                         audioPreview.classList.remove('hidden-element');
//                         teacherForm.classList.remove('hidden-element');
//                         recordBtn.classList.add('hidden-element');
//                         statusText.innerText = "Audio enregistré avec succès";
//                     };

//                     mediaRecorder.start();
//                     isRecording = true;
//                     updateUI(true);
//                 } catch (err) {
//                     console.error("Erreur Micro:", err);
//                     alert("Accès micro refusé.");
//                 }
//             } else {
//                 mediaRecorder.stop();
//                 isRecording = false;
//                 updateUI(false);
//             }
//         });

//         function updateUI(recording) {
//             if (recording) {
//                 recordBtn.classList.add('bg-red-500', 'pulse-red');
//                 recordBtn.classList.remove('bg-primary');
//                 micIcon.classList.replace('fa-microphone', 'fa-stop');
//                 statusText.innerText = "Enregistrement... cliquez pour arrêter";
//                 statusText.classList.replace('text-primary', 'text-red-500');
//             } else {
//                 recordBtn.classList.remove('bg-red-500', 'pulse-red');
//                 recordBtn.classList.add('bg-primary');
//                 micIcon.classList.replace('fa-stop', 'fa-microphone');
//             }
//         }

//         deleteAudio.addEventListener('click', () => {
//             audioChunks = [];
//             realAudioInput.value = ""; // Vide l'input file
//             audioPlayback.src = "";
//             audioPreview.classList.add('hidden-element');
//             teacherForm.classList.add('hidden-element');
//             recordBtn.classList.remove('hidden-element');
//             statusText.innerText = "Cliquez pour enregistrer";
//             statusText.classList.replace('text-red-500', 'text-primary');
//         });



const audioInput = document.getElementById('audioFile');
        const dropZone = document.getElementById('dropZone');
        const uploadUI = document.getElementById('uploadUI');
        const successUI = document.getElementById('successUI');
        const fileNameDisplay = document.getElementById('fileName');
        const fileSizeDisplay = document.getElementById('fileSize');
        const audioPreview = document.getElementById('audioPreview');
        const removeFile = document.getElementById('removeFile');

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

        // Gestion du changement de fichier
        audioInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                
                // Vérification si c'est bien de l'audio
                if (!file.type.startsWith('audio/')) {
                    alert("Veuillez sélectionner un fichier audio valide.");
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
            
            uploadUI.classList.remove('hidden');
            successUI.classList.add('hidden');
            successUI.classList.remove('flex');
            dropZone.classList.remove('drop-zone--success');
            dropZone.classList.add('border-dashed');
        });