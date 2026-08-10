document.addEventListener("DOMContentLoaded", () => {
  const photoInput = document.getElementById("photo-input");
  if (!photoInput) return;

  photoInput.addEventListener("change", function (e) {
    const file = this.files[0];
    const previewContainer = document.getElementById("photo-preview-container");
    const placeholder = document.getElementById("preview-placeholder");
    const imgDisplay = document.getElementById("image-display");

    if (!file || !previewContainer || !placeholder || !imgDisplay) return;

    const reader = new FileReader();

    reader.onload = function (event) {
      imgDisplay.src = event.target.result;
      imgDisplay.classList.remove("hidden");
      placeholder.classList.add("hidden");
      previewContainer.classList.add("border-primary");
    };

    reader.readAsDataURL(file);
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const panels = Array.from(document.querySelectorAll(".step-panel"));
  const dots = Array.from(document.querySelectorAll(".step-dot"));
  const labels = Array.from(document.querySelectorAll(".step-label"));
  const nextBtn = document.getElementById("next-step");
  const prevBtn = document.getElementById("prev-step");
  const submitBtn = document.getElementById("submit-step");
  const saveBtn = document.getElementById("save-progress");
  const form = document.getElementById("integration-form");
  const introScreen = document.getElementById("integration-intro");
  const formWrapper = document.getElementById("integration-form-wrapper");
  const startBtn = document.getElementById("start-integration-btn");

  const inputDate = document.getElementById("date_naissance");
  const aujourdhui = new Date();

  // On calcule l'année maximum autorisée (Année actuelle - 14)
  const anneeMax = aujourdhui.getFullYear() - 14;
  const mois = String(aujourdhui.getMonth() + 1).padStart(2, "0"); // Les mois vont de 0 à 11 en JS
  const jour = String(aujourdhui.getDate()).padStart(2, "0");

  // Format attendu par l'input date : YYYY-MM-DD
  const dateMaxAutorisee = `${anneeMax}-${mois}-${jour}`;

  // On applique la restriction à l'input et on garde le champ vide au chargement
  if (inputDate) {
    inputDate.setAttribute("max", dateMaxAutorisee);
    if (!inputDate.value) {
      inputDate.value = "";
    }
  }

  if (
    !panels.length ||
    !nextBtn ||
    !prevBtn ||
    !submitBtn ||
    !form ||
    !saveBtn
  ) {
    return;
  }

  let currentStep = 0;

  function renderStep() {
    panels.forEach((panel, index) => {
      panel.classList.toggle("hidden", index !== currentStep);
    });

    dots.forEach((dot, index) => {
      dot.classList.toggle("bg-primary", index <= currentStep);
      dot.classList.toggle("bg-gray-200", index > currentStep);
    });

    labels.forEach((label, index) => {
      label.classList.toggle("text-primary", index === currentStep);
      label.classList.toggle("text-gray-400", index !== currentStep);
    });

    prevBtn.classList.toggle("hidden", currentStep === 0);
    nextBtn.classList.toggle("hidden", currentStep === panels.length - 1);
    submitBtn.classList.toggle("hidden", currentStep !== panels.length - 1);
  }

  function validateCurrentStep() {
    const panel = panels[currentStep];
    if (!panel) return true;

    const fields = Array.from(
      panel.querySelectorAll("input, select, textarea"),
    );
    for (const field of fields) {
      if (field.disabled || field.type === "file") {
        continue;
      }

      if (field.required) {
        field.setCustomValidity("");
        if (!field.checkValidity()) {
          const value = field.value ? field.value.trim() : "";
          if (!value) {
            field.setCustomValidity("Veuillez remplir ce champ.");
          }
          field.reportValidity();
          field.focus();
          field.classList.add("border-red-400");
          setTimeout(() => field.classList.remove("border-red-400"), 1800);
          return false;
        }
      }
    }

    return true;
  }

  function serializeForm() {
    const formData = new FormData(form);
    const data = {};
    for (const [key, value] of formData.entries()) {
      if (form.querySelector(`[name="${key}"]`)?.type === "file") continue;
      data[key] = value;
    }
    return data;
  }

  function restoreSavedProgress() {
    const saved = localStorage.getItem("lobolaIntegrationProgress");
    if (!saved) return;

    try {
      const data = JSON.parse(saved);
      Object.entries(data).forEach(([name, value]) => {
        const field = form.querySelector(`[name="${name}"]`);
        if (!field) return;

        if (field.type === "radio" || field.type === "checkbox") {
          field.checked = field.value === value;
        } else {
          field.value = value;
        }
      });

      const status = document.getElementById("save-status");
      if (status) {
        status.textContent = "Progression restaurée.";
      }
    } catch (error) {
      localStorage.removeItem("lobolaIntegrationProgress");
    }
  }

  function saveProgress() {
    const data = serializeForm();
    localStorage.setItem("lobolaIntegrationProgress", JSON.stringify(data));
    const status = document.getElementById("save-status");
    if (status) {
      status.textContent = "Progression sauvegardée.";
    }
    if (saveBtn) {
      saveBtn.disabled = true;
    }
  }

  function clearSavedProgress() {
    localStorage.removeItem("lobolaIntegrationProgress");
  }

  function enableSaveButton() {
    if (saveBtn) {
      saveBtn.disabled = false;
    }
  }

  function attachChangeListeners() {
    const inputs = Array.from(form.querySelectorAll("input, select, textarea"));
    inputs.forEach((field) => {
      field.addEventListener("input", () => {
        enableSaveButton();
      });
    });
  }

  startBtn?.addEventListener("click", () => {
    introScreen?.classList.add("hidden");
    formWrapper?.classList.remove("hidden");
    renderStep();
  });

  nextBtn.addEventListener("click", () => {
    if (!validateCurrentStep()) return;
    currentStep = Math.min(currentStep + 1, panels.length - 1);
    renderStep();
  });

  prevBtn.addEventListener("click", () => {
    currentStep = Math.max(currentStep - 1, 0);
    renderStep();
  });

  saveBtn?.addEventListener("click", () => {
    saveProgress();
  });

  form.addEventListener("submit", (event) => {
    if (!validateCurrentStep()) {
      event.preventDefault();
      return;
    }
    clearSavedProgress();
  });

  restoreSavedProgress();
  attachChangeListeners();
  renderStep();
});
