const body = document.body;
const html = document.documentElement;
const dataTheme = html.getAttribute('data-bs-theme');

const dropdownItems = document.querySelectorAll('.di-theme');
const listTheme = document.getElementById('listTheme');
const btnListTheme = listTheme.querySelectorAll('button');
const svgThemeLight = document.getElementById('svgThemeLight');
const svgThemeDark = document.getElementById('svgThemeDark');
const svgThemeAuto = document.getElementById('svgThemeAuto');

function applyTheme(theme) {
    html.setAttribute('data-bs-theme', theme);
    svgThemeLight.classList.add('d-none');
    svgThemeDark.classList.add('d-none');
    svgThemeAuto.classList.add('d-none')

    switchSvg(theme);
}

const savedTheme = localStorage.getItem('theme');
if(savedTheme) {
    html.setAttribute('data-bs-theme', savedTheme);
    switchSvg(savedTheme)
} 
   

function updateDropdownUI(current) {
    dropdownItems.forEach(item => {
        item.classList.remove('active');
        if (item.getAttribute('data-bs-theme-value') === current) {
            item.classList.add('bg-info');
        } 
        else {
            item.classList.remove('bg-info');
        }
    });
}

function switchSvg(theme)
{
    switch(theme) {
        case 'light':
            svgThemeLight.classList.remove('d-none');
            break;
        case 'dark':
            svgThemeDark.classList.remove('d-none');
            break;
        case 'auto':
            svgThemeAuto.classList.remove('d-none');
            break;
    }
}

updateDropdownUI(savedTheme)

dropdownItems.forEach(item => {
    item.addEventListener('click', (e) => {
        e.preventDefault();
        const selected = item.getAttribute('data-bs-theme-value');
        localStorage.setItem('theme', selected);
        applyTheme(selected);
        updateDropdownUI(selected);
    });
});




// timer
function formatTimer(ms) {
    const totalSeconds = Math.floor(ms / 1000);
    const days = Math.floor(totalSeconds / (3600 * 24));
    const hours = Math.floor((totalSeconds % (3600 * 24)) / 3600);
    const minutes = Math.floor((totalSeconds % 3600) / 60);
    const seconds = totalSeconds % 60;
    // Format the output
    return `${days}j ${hours}h ${minutes}m ${seconds}s`;
}

window.addEventListener('DOMContentLoaded', () => {
    let divs = document.querySelectorAll('.date_limite');
    
    divs.forEach(div => {
        const originalText = div.textContent.trim();
        const dateStr = div.getAttribute('data-datetime');
        const targetDate = new Date(dateStr.replace(' ', 'T'));
        
        if(isNaN(targetDate.getTime())) return;        

        function updateTimer() {   
            const now = new Date();
            const diff = targetDate - now;

            if (diff <= 0 && diff != undefined) {
                div.textContent = originalText;
                
            } else {
                div.textContent = formatTimer(diff);
            }
        }  
        // Update the timer every second
        updateTimer();
        setInterval(updateTimer, 1000);
    })
            
});


window.addEventListener('DOMContentLoaded', () => {
    // other
    const selectTransmission = document.getElementById('selectTransmission');
    const divInputsDate = document.getElementById('divInputsDate');

    function toggleDates()
    {
        if (selectTransmission.value == 'ordre de service') divInputsDate.style.display = 'flex';
        else divInputsDate.style.display = 'none';
    }
    toggleDates();
    selectTransmission.addEventListener('change', toggleDates);		
});



    /* ------------------------------
        IndexedDB Setup
    ------------------------------ */
    let db;
    const request = indexedDB.open("pdf_store", 1);

    request.onupgradeneeded = (e) => {
        db = e.target.result;
        db.createObjectStore("files", { keyPath: "id" });
    };

    request.onsuccess = (e) => {
        db = e.target.result;
        loadStoredFile();  // Recharge le fichier en cache au démarrage
    };
    /* ------------------------------
        Elements DOM
    ------------------------------ */
    const dropZone = document.getElementById("drop-zone");
    const fileInput = document.getElementById("file-input");
    const browseBtn = document.getElementById("browse-btn");

    const progressContainer = document.querySelector(".progress-container");
    const progressBar = document.querySelector(".progress-bar");
    const progressText = document.querySelector(".progress-text");

    const filePreview = document.querySelector(".file-preview");
    const fileNameEl = document.querySelector(".file-name");
    const fileSizeEl = document.querySelector(".file-size");
    const removeBtn = document.querySelector(".file-remove");
    /* ------------------------------
        Gestion Drag & Drop
    ------------------------------ */

    browseBtn.addEventListener("click", () => fileInput.click());

    dropZone.addEventListener("dragover", (e) => {
        e.preventDefault();
        dropZone.classList.add("dragover");
    });

    dropZone.addEventListener("dragleave", () => {
        dropZone.classList.remove("dragover");
    });

    dropZone.addEventListener("drop", (e) => {
        e.preventDefault();
        dropZone.classList.remove("dragover");

        const file = e.dataTransfer.files[0];
        if (file) handleFile(file);
    });

    fileInput.addEventListener("change", () => {
        const file = fileInput.files[0];
        if (file) handleFile(file);
    });
    
    window.addEventListener('load', () => {
        deletePdfUpload();
    })


    /* ------------------------------
        Fonction principale
    ------------------------------ */
    function handleFile(file) {
        saveFile(file); // On stocke dans IndexedDB
        simulateLoading(file);
    }

    /* ------------------------------
        Simulateur Progressbar
    ------------------------------ */
    function simulateLoading(file) {
        progressContainer.style.display = "block";
        progressBar.style.width = "0%";
        progressText.textContent = "0%";

        let percent = 0;

        // Valeurs taille fichier
        const finalSize = file.size;
        const maxDisplaySize = finalSize;
        let currentSize = 0;

        const interval = setInterval(() => {
            percent += 4;
            currentSize = Math.min(finalSize * (percent / 100), finalSize);

            progressBar.style.width = percent + "%";
            progressText.textContent = percent + "%";

            updateFilePreviewLive(file.name, currentSize, finalSize);

            if (percent >= 100) {
                clearInterval(interval);
                progressText.textContent = "100%";
                showFilePreview(file); // Affichage final
            }
        }, 70);
    }

    /* ------------------------------
        Aperçu dynamique en live
    ------------------------------ */
    function updateFilePreviewLive(name, loadedBytes, totalBytes) {
        filePreview.style.display = "flex";

        fileNameEl.textContent = name;

        const loadedFormatted = formatSize(loadedBytes);
        const totalFormatted = formatSize(totalBytes);

        fileSizeEl.textContent = `${loadedFormatted} / ${totalFormatted}`;
    }

    /* ------------------------------
        Aperçu final
    ------------------------------ */
    function showFilePreview(file) {
        filePreview.style.display = "flex";

        fileNameEl.textContent = file.name;

        const totalFormatted = formatSize(file.size);
        fileSizeEl.textContent = `${totalFormatted} / ${totalFormatted}`;
    }

    /* ------------------------------
        Format de la taille
    ------------------------------ */
    function formatSize(bytes) {
        if (bytes >= 1024 * 1024)
            return (bytes / (1024 * 1024)).toFixed(2) + " Mo";
        return (bytes / 1024).toFixed(1) + " Ko";
    }

    /* ------------------------------
        Supprimer le fichier
    ------------------------------ */
    removeBtn.addEventListener("click", () => {
        deletePdfUpload();
    });

    function deletePdfUpload()
    {
        const txn = db.transaction("files", "readwrite");
        txn.objectStore("files").delete(1);

        fileInput.value = "";
        progressContainer.style.display = "none";
        filePreview.style.display = "none";
    }


    /* ------------------------------
        IndexedDB : enregistrer
    ------------------------------ */
    function saveFile(file) {
        const txn = db.transaction("files", "readwrite");
        const store = txn.objectStore("files");

        store.put({ id: 1, file: file });
    }


    /* ------------------------------
        IndexedDB : recharger au démarrage
    ------------------------------ */
    // function loadStoredFile() {
    //     const txn = db.transaction("files", "readonly");
    //     const store = txn.objectStore("files");

    //     const request = store.get(1);
    //     request.onsuccess = () => {
    //         if (request.result) {
    //             const file = request.result.file;

    //             progressContainer.style.display = "block";
    //             progressBar.style.width = "100%";
    //             progressText.textContent = "100%";

    //             showFilePreview(file);
    //         }
    //     };
    // }

    const fichier = "<?= $pathFilePdf ?>";

    window.addEventListener("load", () => {
        setTimeout(() => {
            fetch("/dr/deleteFileView?file=" + encodeURIComponent(fichier))
                .then(res => res.text())
                .then(console.log)
                .catch(console.error);
        }, 1000);
    });
