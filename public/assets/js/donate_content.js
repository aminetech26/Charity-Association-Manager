{
  const ROOT = "http://localhost/TDWProject/";

  function validateFile(input) {
    const file = input.files[0];
    const errorMessage = input.nextElementSibling;

    if (file) {
      const validExtensions = [".jpg", ".jpeg", ".png"];
      const fileExtension = file.name
        .substring(file.name.lastIndexOf("."))
        .toLowerCase();

      if (!validExtensions.includes(fileExtension)) {
        errorMessage.textContent =
          "Veuillez sélectionner un fichier au format PDF, JPG, JPEG ou PNG.";
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
      errorMessage.textContent = "Un fichier est requis.";
      errorMessage.classList.remove("hidden");
      return false;
    }
  }

  document.getElementById("donForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const receiptInput = document.getElementById("receipt");
    if (!validateFile(receiptInput)) {
      return;
    }

    const formData = new FormData(e.target);

    try {
      const response = await fetch(`${ROOT}public/Membre/ajouterDon`, {
        method: "POST",
        body: formData,
      });

      const data = await response.json();

      if (data.status === "success") {
        alert(data.message);
        e.target.reset();
      } else {
        console.error("Erreur lors de l'envoi du don:", data.message);
        alert("Erreur: " + data.message);
      }
    } catch (error) {
      console.error("Erreur lors de l'envoi du don:", error);
      alert("Une erreur s'est produite lors de l'envoi du don.");
    }
  });
}
