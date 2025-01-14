{
  const ROOT = "http://localhost/TDWProject/";
  const tabs = document.querySelectorAll(".tab-link");
  const tabPanes = document.querySelectorAll(".tab-pane");

  tabs.forEach((tab) => {
    tab.addEventListener("click", function (e) {
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
            alert("Erreur: " + data.message); // Affiche un message d'erreur
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
        updateMemberInfoForm(data.data);
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

    document.getElementById("card-member-id").textContent =
      info.member_unique_id;
    document.getElementById(
      "card-member-name"
    ).textContent = `${info.prenom} ${info.nom}`;

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
      qrcodeContainer.innerHTML = `<img src="${finalQrPath}" alt="QR Code" class="w-32 h-32">`;
    }
  }

  loadMemberInfo();
  initializeEventListeners();
}
