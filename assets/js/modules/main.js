const fileInput2 = document.getElementById('file-input2');
const dropZone2 = document.getElementById('drop-zone2');
const uploadPrompt2 = document.getElementById('upload-prompt2');
const uploadSuccess2 = document.getElementById('upload-success2');
const fileNameDisplay2 = document.getElementById('file-name2');

fileInput2.addEventListener('change', function(e) {
    if (this.files && this.files.length > 0) {
        const fileName = this.files[0].name;
        
        // Mise à jour visuelle
        uploadPrompt2.classList.add('hidden');
        uploadSuccess2.classList.remove('hidden');
        // dropZone2.classList.add('file-selected');
        
        // On affiche le nom du fichier (tronqué si trop long)
        // fileNameDisplay2.textContent = fileName.length > 25 ? fileName.substring(0, 22) + "..." : fileName;
    }
});

function resetUpload(e) {
    e.preventDefault();
    e.stopPropagation();
    fileInput.value = ""; // Efface le fichier
    uploadPrompt.classList.remove('hidden');
    uploadSuccess.classList.add('hidden');
    dropZone.classList.remove('file-selected');
}