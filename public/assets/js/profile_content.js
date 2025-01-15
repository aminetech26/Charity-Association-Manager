{
  const ROOT = "http://localhost/TDWProject/";
  const tabs = document.querySelectorAll(".tab-link");
  const tabPanes = document.querySelectorAll(".tab-pane");

  tabs.forEach((tab) => {
    tab.addEventListener("click", function (e) {
      if (this.getAttribute("href") === "#carte") {
        const memberCard = document.getElementById("member-card");
        if (memberCard.classList.contains("hidden")) {
          e.preventDefault();
          alert(
            "Vous devez avoir un abonnement actif pour accéder à votre carte de membre."
          );
          return;
        }
      }
      e.preventDefault();

      tabs.forEach((t) =>
        t.classList.remove("active", "text-blue-600", "border-blue-600")
      );
      tabPanes.forEach((pane) => pane.classList.add("hidden"));

      this.classList.add("active", "text-blue-600", "border-blue-600");
      const target = this.getAttribute("href");
      document.querySelector(target).classList.remove("hidden");
    });
  });

  if (tabs.length > 0) {
    tabs[0].classList.add("text-blue-600", "border-blue-600");
    document
      .querySelector(tabs[0].getAttribute("href"))
      .classList.remove("hidden");
  }

  function initializeEventListeners() {
    document
      .getElementById("memberForm")
      .addEventListener("submit", async (e) => {
        e.preventDefault();

        const formData = new FormData(e.target);

        try {
          const response = await fetch(
            `${ROOT}public/Membre/updateMemberInfos`,
            {
              method: "POST",
              body: formData,
            }
          );

          const data = await response.json();

          if (data.status === "success") {
            alert(data.message);
            loadMemberInfo();
          } else {
            console.error(
              "Erreur lors de la mise à jour des informations:",
              data.message
            );
            alert("Erreur: " + data.message);
          }
        } catch (error) {
          console.error(
            "Erreur lors de la mise à jour des informations:",
            error
          );
          alert(
            "Une erreur s'est produite lors de la mise à jour des informations."
          );
        }
      });
  }

  async function loadMemberInfo() {
    try {
      const response = await fetch(`${ROOT}public/Membre/getMemberInfos`);
      const data = await response.json();

      if (data.status === "success") {
        updateMemberInfoForm(data.data[0]);
      } else {
        console.error("Error loading member info:", data.message);
      }
    } catch (error) {
      console.error("Error loading member info:", error);
    }
  }

  function updateMemberInfoForm(info) {
    document.getElementById("member_id").value = info.member_unique_id;
    document.getElementById("date_creation").value = info.created_at;
    document.getElementById("nom").value = info.nom;
    document.getElementById("prenom").value = info.prenom;
    document.getElementById("email").value = info.email;
    document.getElementById("telephone").value = info.numero_de_telephone;
    document.getElementById("adresse").value = info.adresse;

    const memberCard = document.getElementById("member-card");
    const noSubscriptionMessage = document.getElementById(
      "no-subscription-message"
    );

    if (!info.abonnement_id) {
      memberCard.classList.add("hidden");
      noSubscriptionMessage.classList.remove("hidden");
      return;
    }

    memberCard.classList.remove("hidden");
    noSubscriptionMessage.classList.add("hidden");

    document.getElementById("card-member-id").textContent =
      info.member_unique_id;
    document.getElementById(
      "card-member-name"
    ).textContent = `${info.prenom} ${info.nom}`;
    document.getElementById("card-subscription-type").textContent =
      info.abonnement_type_abonnement;
    document.getElementById("card-expiry-date").textContent = formatDate(
      info.abonnement_date_fin
    );

    if (info.association_logo) {
      let path = info.association_logo;
      let trimmedPath = path.includes("public/")
        ? path.split("public/")[1]
        : path;
      let finalPath = `${ROOT}public/${trimmedPath}`;
      document.getElementById("association-logo").src = finalPath;
    } else {
      document.getElementById(
        "association-logo"
      ).src = `${ROOT}public/assets/images/logo.png`;
    }

    if (info.qr_code) {
      let qrPath = info.qr_code;
      let trimmedQrPath = qrPath.includes("public/")
        ? qrPath.split("public/")[1]
        : qrPath;
      let finalQrPath = `${ROOT}public/${trimmedQrPath}`;
      const qrcodeContainer = document.getElementById("qrcode");
      qrcodeContainer.innerHTML = `
        <img src="${finalQrPath}" 
             alt="QR Code" 
             class="w-24 h-24 object-contain"
        >`;
    }
  }

  function formatDate(dateString) {
    if (!dateString) return "";
    const date = new Date(dateString);
    return date.toLocaleDateString("fr-FR", {
      day: "numeric",
      month: "long",
      year: "numeric",
    });
  }

  loadMemberInfo();
  initializeEventListeners();
}
