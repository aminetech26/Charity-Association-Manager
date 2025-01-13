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

  async function verifyMember(memberId, verificationType = "") {
    try {
      const formData = new FormData();
      formData.append("member_unique_id", memberId);
      formData.append("verification_type", verificationType);

      const response = await fetch(
        `${ROOT}public/Partenaire/checkIfMemberEligible`,
        {
          method: "POST",
          body: formData,
        }
      );

      const data = await response.json();
      return data;
    } catch (error) {
      console.error("Error during verification:", error);
      return {
        status: "error",
        message: "Une erreur s'est produite lors de la vérification",
        isSubscribed: false,
      };
    }
  }

  function displayQRCode(qrCodeData) {
    if (!qrCodeData) return "";

    let path = qrCodeData;
    let trimmedPath = qrCodeData.includes("public/")
      ? path.split("public/")[1]
      : path;
    return `
        <div class="bg-white p-4 rounded-lg shadow-sm mb-4">
          <img src="${ROOT}public/${trimmedPath}" alt="QR Code" class="mx-auto w-48 h-48 object-cover">
          <p class="text-sm text-gray-600 mt-2 text-center">QR Code du membre</p>
        </div>
      `;
  }

  function showVerificationResult(
    elementId,
    verificationData,
    verificationType
  ) {
    if (verificationType === "qr") {
      const qrResult = document.getElementById("qrResult");
      if (qrResult) {
        qrResult.classList.remove("hidden");
      }
    }

    const element = document.getElementById(elementId);

    element.classList.remove("hidden");

    if (verificationData.status === "success") {
      element.className =
        "text-center p-4 rounded-lg bg-green-100 border border-green-400 text-green-700";
      element.innerHTML = `
            <svg class="w-16 h-16 mx-auto mb-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="font-bold text-lg mb-2">Membre Éligible</p>
            <p class="mb-2">${verificationData.message}</p>
            ${
              verificationData.offer
                ? `
                <div class="mt-4 p-3 bg-white rounded shadow-sm">
                    <p class="font-medium mb-1">Détails de l'offre:</p>
                    <p class="text-sm">${verificationData.offer.description}</p>
                    <p class="text-sm mt-1">Valeur: ${verificationData.offer.valeur}</p>
                    <p class="text-sm">Type: ${verificationData.memberType}</p>
                </div>
            `
                : ""
            }
        `;
    } else {
      element.className =
        "text-center p-4 rounded-lg bg-red-100 border border-red-400 text-red-700";

      let errorTitle = "Erreur de Vérification";
      if (!verificationData.isSubscribed) {
        errorTitle = "Membre Non Abonné";
      } else if (verificationData.noOffers) {
        errorTitle = "Aucune Offre Disponible";
      }

      element.innerHTML = `
            <svg class="w-16 h-16 mx-auto mb-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="font-bold text-lg mb-2">${errorTitle}</p>
            <p>${verificationData.message}</p>
            ${
              verificationData.noOffers
                ? `
                <div class="mt-4 p-3 bg-white rounded shadow-sm">
                    <p class="text-sm">Type d'abonnement: ${
                      verificationData.memberType || "Non spécifié"
                    }</p>
                </div>
            `
                : ""
            }
        `;
    }

    console.log(verificationData.qrCode);

    if (verificationType === "qr" && verificationData.qrCode) {
      console.log(verificationData.qrCode);
      const qrImageDiv = document.getElementById("qrImage");
      if (qrImageDiv) {
        qrImageDiv.innerHTML = displayQRCode(verificationData.qrCode);
      }
    }
  }

  function initializeEventListeners() {
    document
      .getElementById("generateQR")
      ?.addEventListener("click", async function () {
        const memberId = document.getElementById("qrMemberId").value;
        if (!memberId) {
          alert("Veuillez entrer l'identifiant du membre");
          return;
        }

        const qrResult = document.getElementById("qrResult");
        if (!qrResult) {
          console.error("QR result element not found");
          return;
        }

        qrResult.classList.remove("hidden");

        const response = await verifyMember(memberId, "qr");
        showVerificationResult("qrVerificationResult", response, "qr");
      });

    document
      .getElementById("verifyId")
      ?.addEventListener("click", async function () {
        const memberId = document.getElementById("directMemberId").value;
        if (!memberId) {
          alert("Veuillez entrer l'identifiant du membre");
          return;
        }

        const idVerificationResult = document.getElementById(
          "idVerificationResult"
        );
        if (!idVerificationResult) {
          console.error("ID verification result element not found");
          return;
        }

        idVerificationResult.classList.remove("hidden");
        idVerificationResult.innerHTML = `
            <div class="text-center p-4">
              <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
              <p class="mt-2 text-gray-600">Vérification en cours...</p>
            </div>
          `;

        const response = await verifyMember(memberId, "direct");
        showVerificationResult("idVerificationResult", response, "direct");
      });
  }

  initializeEventListeners();
}
