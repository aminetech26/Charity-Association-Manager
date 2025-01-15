{
  const ROOT = "http://localhost/TDWProject/public";

  const fileInput = document.getElementById("recu");
  const fileNameDisplay = document.getElementById("file-name");

  fileInput?.addEventListener("change", (e) => {
    const fileName = e.target.files[0]?.name;
    if (fileName) {
      fileNameDisplay.textContent = `Fichier sélectionné: ${fileName}`;
    } else {
      fileNameDisplay.textContent = "";
    }
  });

  const subscriptionTypes = document.querySelectorAll(".subscription-type");
  subscriptionTypes.forEach((radio) => {
    radio.addEventListener("change", () => {
      document.querySelectorAll(".subscription-border").forEach((border) => {
        border.classList.remove("border-blue-500");
        border
          .closest("label")
          .classList.remove("border-blue-500", "ring-2", "ring-blue-500");
      });

      if (radio.checked) {
        const label = radio.closest("label");
        const border = label.querySelector(".subscription-border");
        border.classList.add("border-blue-500");
        label.classList.add("border-blue-500", "ring-2", "ring-blue-500");
      }
    });
  });

  const showFormButton = document.getElementById("show-subscription-form");
  const subscriptionForm = document.getElementById("subscription-form");
  const cancelButton = document.getElementById("cancel-subscription");

  showFormButton?.addEventListener("click", () => {
    subscriptionForm.classList.remove("hidden");
    showFormButton.classList.add("hidden");
  });

  cancelButton?.addEventListener("click", () => {
    subscriptionForm.classList.add("hidden");
    showFormButton.classList.remove("hidden");
    document.getElementById("abonnement-form").reset();
    fileNameDisplay.textContent = "";

    document.querySelectorAll(".subscription-border").forEach((border) => {
      border.classList.remove("border-blue-500");
      border
        .closest("label")
        .classList.remove("border-blue-500", "ring-2", "ring-blue-500");
    });
  });

  function calculateTimeRemaining(endDate) {
    const now = new Date();
    const end = new Date(endDate);
    const diff = end - now;

    if (diff <= 0) {
      return "Abonnement expiré";
    }

    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
    return `${days} jours restants`;
  }

  let isRenewal = false; // initialiser à faux lehna pas a vrai

  async function loadSubscriptionInfo() {
    try {
      const response = await fetch(`${ROOT}/Membre/getMemberInfos`);
      const data = await response.json();

      if (data.status === "success" && data.data?.[0]) {
        const info = data.data[0];
        const hasSubscription = info.abonnement_id !== null;
        const subscriptionForm = document.getElementById("subscription-form");
        const showFormButton = document.getElementById(
          "show-subscription-form"
        );

        document
          .getElementById("has-subscription")
          .classList.toggle("hidden", !hasSubscription);
        document
          .getElementById("no-subscription")
          .classList.toggle("hidden", hasSubscription);

        if (hasSubscription) {
          document.getElementById("type-abonnement").textContent =
            info.abonnement_type_abonnement;
          document.getElementById("statut-abonnement").textContent =
            info.abonnement_statut || "En cours";
          document.getElementById("date-debut").textContent = formatDate(
            info.abonnement_date_debut
          );
          document.getElementById("date-fin").textContent = formatDate(
            info.abonnement_date_fin
          );
          document.getElementById("temps-restant").textContent =
            calculateTimeRemaining(info.abonnement_date_fin);

          const isExpired = new Date(info.abonnement_date_fin) < new Date();

          if (isExpired) {
            showFormButton.classList.remove("hidden");
            document.getElementById("btn-text").textContent =
              "Renouveler votre abonnement";
            isRenewal = true;
          } else {
            showFormButton.classList.add("hidden");
            subscriptionForm.classList.add("hidden");
          }
        } else {
          showFormButton.classList.remove("hidden");
          document.getElementById("btn-text").textContent =
            "Souscrire à un abonnement";
          isRenewal = false;
        }
      }
    } catch (error) {
      console.error("Error loading subscription info:", error);
    }
  }

  document
    .getElementById("abonnement-form")
    ?.addEventListener("submit", async (e) => {
      e.preventDefault();
      const formData = new FormData(e.target);

      try {
        const endpoint = isRenewal ? "renouvelerAbonnement" : "creerAbonnement";
        const response = await fetch(`${ROOT}/Membre/${endpoint}`, {
          method: "POST",
          body: formData,
        });

        const data = await response.json();

        if (data.status === "success") {
          alert(data.message);
          location.reload();
        } else {
          alert(data.message || "Une erreur est survenue");
        }
      } catch (error) {
        console.error("Error:", error);
        alert("Une erreur est survenue lors de la soumission");
      }
    });

  function formatDate(dateString) {
    if (!dateString) return "N/A";
    return new Date(dateString).toLocaleDateString("fr-FR", {
      day: "numeric",
      month: "long",
      year: "numeric",
    });
  }

  loadSubscriptionInfo();
}
