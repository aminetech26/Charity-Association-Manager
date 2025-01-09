const ROOT = "http://localhost/TDWProject/";
const fileValidationRules = {
  photo: {
    allowedTypes: ["image/jpeg", "image/png", "image/jpg"],
    maxSize: 2, // MB
    messages: {
      type: "Seuls les formats JPG et PNG sont acceptés pour la photo",
      required: "Une photo est requise",
      size: "La taille du fichier ne doit pas dépasser 2MB",
    },
  },
  piece_identite: {
    allowedTypes: ["image/jpeg", "image/png", "image/jpg"],
    maxSize: 2, // MB
    messages: {
      type: "Seuls les formats JPG, PNG sont acceptés pour la pièce d'identité",
      required: "La pièce d'identité est requise",
      size: "La taille du fichier ne doit pas dépasser 2MB",
    },
  },
  recu_paiement: {
    allowedTypes: ["image/jpeg", "image/png", "image/jpg"],
    maxSize: 5,
    messages: {
      type: "Seuls les formats JPG, PNG sont acceptés pour le reçu",
      required: "Le reçu est requis",
      size: "La taille du fichier ne doit pas dépasser 5MB",
    },
  },
};

const validationRules = {
  nom: {
    pattern: /^[a-zA-ZÀ-ÿ\s]{2,}$/,
    message: "Le nom doit contenir au moins 2 caractères alphabétiques",
  },
  prenom: {
    pattern: /^[a-zA-ZÀ-ÿ\s]{2,}$/,
    message: "Le prénom doit contenir au moins 2 caractères alphabétiques",
  },
  numero_de_telephone: {
    pattern: /^[\d\s+()-]{10,}$/,
    message:
      "Veuillez entrer un numéro de téléphone valide (minimum 10 chiffres)",
  },
  adresse: {
    pattern: /^[a-zA-Z0-9\s,.-]{5,}$/,
    message: "L'adresse doit contenir au moins 5 caractères",
  },
  email: {
    pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
    message: "Veuillez entrer une adresse email valide",
  },
  mot_de_passe: {
    pattern: /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*#?&]{8,}$/,
    message:
      "Le mot de passe doit contenir au moins 8 caractères, dont une lettre et un chiffre",
  },
};

// File validation function
function validateFile(input) {
  const fileRules = fileValidationRules[input.id];
  const errorElement = input.nextElementSibling;
  const file = input.files[0];

  input.classList.remove("border-red-500", "border-green-500");
  errorElement.classList.add("hidden");

  if (!file) {
    if (input.required) {
      input.classList.add("border-red-500");
      errorElement.textContent = fileRules.messages.required;
      errorElement.classList.remove("hidden");
      return false;
    }
    return true;
  }

  const isValidType = fileRules.allowedTypes.includes(file.type);
  if (!isValidType) {
    input.classList.add("border-red-500");
    errorElement.textContent = fileRules.messages.type;
    errorElement.classList.remove("hidden");
    return false;
  }

  const isValidSize = file.size <= fileRules.maxSize * 1024 * 1024; // Convert MB to bytes
  if (!isValidSize) {
    input.classList.add("border-red-500");
    errorElement.textContent = fileRules.messages.size;
    errorElement.classList.remove("hidden");
    return false;
  }

  input.classList.add("border-green-500");
  return true;
}

function validateField(input) {
  const rule = validationRules[input.id];
  const errorElement = input.nextElementSibling;

  if (!rule) return true;

  const isValid = rule.pattern.test(input.value);
  input.classList.toggle("border-red-500", !isValid);
  input.classList.toggle("border-green-500", isValid);

  if (!isValid) {
    errorElement.textContent = rule.message;
    errorElement.classList.remove("hidden");
  } else {
    errorElement.classList.add("hidden");
  }

  return isValid;
}

function validatePassword() {
  const password = document.getElementById("mot_de_passe");
  const errorElement = password.nextElementSibling;

  const isValid = password.value.length >= 8;
  password.classList.toggle("border-red-500", !isValid);
  password.classList.toggle("border-green-500", isValid);

  if (!isValid) {
    errorElement.textContent =
      "Le mot de passe doit contenir au moins 8 caractères";
    errorElement.classList.remove("hidden");
  } else {
    errorElement.classList.add("hidden");
  }

  return isValid;
}

function validatePasswordConfirm() {
  const password = document.getElementById("mot_de_passe");
  const confirmPassword = document.getElementById("confirmPassword");
  const errorElement = confirmPassword.nextElementSibling;

  const isValid = password.value === confirmPassword.value;
  confirmPassword.classList.toggle("border-red-500", !isValid);
  confirmPassword.classList.toggle("border-green-500", isValid);

  if (!isValid) {
    errorElement.textContent = "Les mots de passe ne correspondent pas";
    errorElement.classList.remove("hidden");
  } else {
    errorElement.classList.add("hidden");
  }

  return isValid;
}

function validateStep(step) {
  const currentStepElement = document.getElementById(`step-${step}`);
  const inputs = currentStepElement.querySelectorAll("input[required]");
  let isValid = true;

  inputs.forEach((input) => {
    if (input.type === "file") {
      isValid = validateFile(input) && isValid;
    } else {
      isValid = validateField(input) && isValid;
    }
  });

  if (step === 3) {
    isValid = validatePasswordConfirm() && isValid;
  }

  return isValid;
}

let currentStep = 1;
const form = document.getElementById("multi-step-form");
const prevBtn = document.getElementById("prevBtn");
const nextBtn = document.getElementById("nextBtn");
const submitBtn = document.getElementById("submitBtn");
const progressBar = document.getElementById("progress-bar");

function updateStepIndicators(step) {
  for (let i = 1; i <= 3; i++) {
    const stepIndicator = document.getElementById(`step${i}`);
    if (stepIndicator) {
      if (i <= step) {
        // Active or completed step
        stepIndicator.classList.remove("bg-gray-200", "text-gray-500");
        stepIndicator.classList.add("bg-primary", "text-white");
      } else {
        // Upcoming step
        stepIndicator.classList.remove("bg-primary", "text-white");
        stepIndicator.classList.add("bg-gray-200", "text-gray-500");
      }
    }
  }
}

function showStep(step) {
  document.querySelectorAll(".step").forEach((s) => s.classList.add("hidden"));
  document.getElementById(`step-${step}`).classList.remove("hidden");

  const totalSteps = 3;
  const progressPercentage = (step / totalSteps) * 100;
  progressBar.style.width = `${progressPercentage}%`;

  updateStepIndicators(step);

  prevBtn.classList.toggle("hidden", step === 1);
  nextBtn.classList.toggle("hidden", step === 3);
  submitBtn.classList.toggle("hidden", step !== 3);
}

nextBtn.addEventListener("click", () => {
  if (validateStep(currentStep)) {
    currentStep++;
    showStep(currentStep);
  }
});

prevBtn.addEventListener("click", () => {
  currentStep--;
  showStep(currentStep);
});

form.addEventListener("submit", async (e) => {
  e.preventDefault();

  if (validateStep(currentStep)) {
    const formData = new FormData(form);

    try {
      const response = await fetch(`${ROOT}public/Membre/signup`, {
        method: "POST",
        body: formData,
      });

      const data = await response.json();

      if (data.status === "success") {
        alert("Compte créé avec succès !");
        window.location.href = ROOT + "public/Home/index";
      } else {
        alert(data.message || "Une erreur s'est produite");
      }
    } catch (error) {
      console.error("Error submitting form:", error);
      alert("Une erreur s'est produite lors de la soumission du formulaire.");
    }
  }
});

document.querySelectorAll("input[required]").forEach((input) => {
  if (input.type !== "file") {
    input.addEventListener("blur", () => validateField(input));
  } else {
    input.addEventListener("change", () => validateFile(input));
  }
});

showStep(currentStep);
