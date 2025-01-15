{
  const ROOT = "http://localhost/TDWProject/public";

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

      loadTabContent(target.substring(1));
    });
  });

  const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString("fr-FR", {
      day: "numeric",
      month: "long",
      year: "numeric",
    });
  };

  async function loadTabContent(tabId) {
    switch (tabId) {
      case "donations":
        await loadDonations();
        break;
      case "volunteering":
        await loadVolunteering();
        break;
      case "discounts":
        await loadDiscounts();
        break;
      case "assistance":
        await loadAssistance();
        break;
    }
  }

  async function loadDonations() {
    try {
      const response = await fetch(`${ROOT}/Membre/getMemberDonations`);
      const data = await response.json();

      if (data.status === "success") {
        const tbody = document.getElementById("donations-table-body");
        const donations = Array.isArray(data.data) ? data.data : [];
        tbody.innerHTML = donations
          .map(
            (don) => `
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4">${formatDate(don.date)}</td>
                        <td class="px-6 py-4">${don.montant} DZD</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs ${getStatusClass(
                              don.statut
                            )}">
                                ${don.statut}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <button onclick="window.open('${ROOT}/${
              don.recu_paiement
            }', '_blank')" 
                                    class="text-blue-600 hover:text-blue-800">
                                Voir le reçu
                            </button>
                        </td>
                    </tr>
                `
          )
          .join("");
      }
    } catch (error) {
      console.error("Error loading donations:", error);
    }
  }

  async function loadVolunteering() {
    try {
      const response = await fetch(`${ROOT}/Membre/getMemberVolunteering`);
      const data = await response.json();

      if (data.status === "success") {
        const tbody = document.getElementById("volunteering-table-body");
        const volunteering = Array.isArray(data.data) ? data.data : [];
        tbody.innerHTML = volunteering
          .map(
            (benevolat) => `
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4">${benevolat.evenement_titre}</td>
                        <td class="px-6 py-4">${formatDate(
                          benevolat.evenement_date_debut
                        )}</td>
                        <td class="px-6 py-4">${formatDate(
                          benevolat.evenement_date_fin
                        )}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs ${getStatusClass(
                              benevolat.statut
                            )}">
                                ${benevolat.statut}
                            </span>
                        </td>
                    </tr>
                `
          )
          .join("");
      }
    } catch (error) {
      console.error("Error loading volunteering:", error);
    }
  }

  async function loadDiscounts() {
    try {
      const response = await fetch(`${ROOT}/Membre/getMemberDiscounts`);
      const data = await response.json();

      if (data.status === "success") {
        const tbody = document.getElementById("discounts-table-body");
        const discounts = Array.isArray(data.data) ? data.data : [];
        tbody.innerHTML = discounts
          .map(
            (remise) => `
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4">${formatDate(
                          remise.date_benefice
                        )}</td>
                        <td class="px-6 py-4">${remise.partenaire_nom}</td>
                        <td class="px-6 py-4">${remise.offre_type_offre}</td>
                        <td class="px-6 py-4">${remise.offre_valeur}</td>
                    </tr>
                `
          )
          .join("");
      }
    } catch (error) {
      console.error("Error loading discounts:", error);
    }
  }

  async function loadAssistance() {
    try {
      const response = await fetch(
        `${ROOT}/Membre/getMemberAssistanceRequests`
      );
      const data = await response.json();

      if (data.status === "success") {
        const tbody = document.getElementById("assistance-table-body");
        const assistance = Array.isArray(data.data) ? data.data : [];
        tbody.innerHTML = assistance
          .map(
            (demande) => `
              <tr class="bg-white border-b hover:bg-gray-50">
                  <td class="px-6 py-4">${formatDate(demande.created_at)}</td>
                  <td class="px-6 py-4">${demande.type_aide_label}</td>
                  <td class="px-6 py-4">${demande.description}</td>
                  <td class="px-6 py-4">
                      <span class="px-2 py-1 rounded-full text-xs ${getStatusClass(
                        demande.statut
                      )}">
                          ${demande.statut ?? "EN_ATTENTE"}
                      </span>
                  </td>
                  <td class="px-6 py-4">
                      <button onclick="window.open('${ROOT}/${
              demande.fichier_zip
            }', '_blank')" 
                              class="text-blue-600 hover:text-blue-800">
                          Voir les documents
                      </button>
                  </td>
              </tr>
          `
          )
          .join("");
      }
    } catch (error) {
      console.error("Error loading assistance requests:", error);
    }
  }

  function getStatusClass(status) {
    switch (status?.toUpperCase()) {
      case "EN_ATTENTE":
        return "bg-yellow-100 text-yellow-800";
      case "ACCEPTE":
      case "EN_COURS":
        return "bg-green-100 text-green-800";
      case "REFUSE":
        return "bg-red-100 text-red-800";
      case "TERMINE":
        return "bg-gray-100 text-gray-800";
      default:
        return "bg-gray-100 text-gray-800";
    }
  }

  document.querySelector(".tab-link.active")?.click();
}
