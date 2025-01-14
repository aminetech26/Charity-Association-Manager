{
  const ROOT = "http://localhost/TDWProject/";
  function validateFile(input) {
    const file = input.files[0];
    const errorMessage = input.nextElementSibling;

    if (file) {
      if (!file.name.endsWith(".zip") && !file.name.endsWith(".rar")) {
        errorMessage.textContent =
          "Veuillez sélectionner un fichier au format ZIP ou RAR.";
        errorMessage.classList.remove("hidden");
        input.value = "";
        return false;
      }

      const maxSize = 10 * 1024 * 1024;
      if (file.size > maxSize) {
        errorMessage.textContent = "Le fichier ne doit pas dépasser 10 Mo.";
        errorMessage.classList.remove("hidden");
        input.value = "";
        return false;
      }

      errorMessage.classList.add("hidden");
      return true;
    } else {
      errorMessage.textContent = "Un fichier ZIP est requis.";
      errorMessage.classList.remove("hidden");
      return false;
    }
  }

  document.getElementById("aideForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const fichierZipInput = document.getElementById("fichier_zip");
    if (!validateFile(fichierZipInput)) {
      return;
    }

    const formData = new FormData(e.target);

    try {
      const response = await fetch(`${ROOT}public/Membre/ajouterDemandeAide`, {
        method: "POST",
        body: formData,
      });

      const data = await response.json();

      if (data.status === "success") {
        alert(data.message);
        e.target.reset();
      } else {
        console.error("Erreur lors de la demande d'aide:", data.message);
        alert("Erreur: " + data.message);
      }
    } catch (error) {
      console.error("Erreur lors de la demande d'aide:", error);
      alert("Une erreur s'est produite lors de la demande d'aide.");
    }
  });

  async function remplirTypesAide() {
    const typeAideSelect = document.getElementById("type_aide");

    try {
      const response = await fetch(`${ROOT}public/Membre/getTypeAides`);
      const data = await response.json();

      if (data.status === "success") {
        typeAideSelect.innerHTML =
          '<option value="" disabled selected>Sélectionnez un type d\'aide</option>';

        data.data.forEach((type) => {
          const option = document.createElement("option");
          option.value = type.id;
          option.textContent = type.label;
          typeAideSelect.appendChild(option);
        });
      } else {
        console.error(
          "Erreur lors du chargement des types d'aide:",
          data.message
        );
        alert("Erreur lors du chargement des types d'aide.");
      }
    } catch (error) {
      console.error("Erreur lors du chargement des types d'aide:", error);
      alert("Une erreur s'est produite lors du chargement des types d'aide.");
    }
  }

  remplirTypesAide();
}
