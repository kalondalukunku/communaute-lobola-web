

document.getElementById('photo-input').addEventListener('change', function(e) {
    const file = this.files[0];
    const previewContainer = document.getElementById('photo-preview-container');
    const placeholder = document.getElementById('preview-placeholder');
    const imgDisplay = document.getElementById('image-display');

    if (file) {
        const reader = new FileReader();
        
        reader.onload = function(event) {
            imgDisplay.src = event.target.result;
            imgDisplay.classList.remove('hidden');
            placeholder.classList.add('hidden');
            previewContainer.classList.add('border-primary');
        }
        
        reader.readAsDataURL(file);
    }
});