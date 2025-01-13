{
  const ROOT = "http://localhost/TDWProject/";
  const tabs = document.querySelectorAll(".tab-link");
  const tabPanes = document.querySelectorAll(".tab-pane");

  // Tab switching logic
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

  // offres section
  const offrePagination = {
    currentPage: 1,
    itemsPerPage: 10,
    totalItems: 0,
    totalPages: 1,
  };

  loadPartnerInfo();
  initializeOffreEventListeners();
  loadOffresFromBackend(
    offrePagination.currentPage,
    offrePagination.itemsPerPage
  );

  async function loadPartnerInfo() {
    try {
      const response = await fetch(`${ROOT}public/Partenaire/getPartnerInfo`);
      const data = await response.json();

      if (data.status === "success") {
        updatePartnerInfoForm(data.data[0]);
      } else {
        console.error("Error loading partner info:", data.message);
      }
    } catch (error) {
      console.error("Error loading partner info:", error);
    }
  }

  function updatePartnerInfoForm(info) {
    let finalPath = "";

    if (info.logo != null) {
      let path = info.logo;
      let trimmedPath = path.includes("public/")
        ? path.split("public/")[1]
        : path;
      finalPath = `${ROOT}public/${trimmedPath}`;
    } else {
      let trimmedPath = "assets/images/placeholder.jpg";
      finalPath = `${ROOT}public/${trimmedPath}`;
    }

    document.getElementById("partner-logo").src = `${finalPath}`;
    document.getElementById("nom").value = info.nom;
    document.getElementById("ville").value = info.ville;
    document.getElementById("email").value = info.email;
    document.getElementById("telephone").value = info.numero_de_telephone;
    document.getElementById("adresse").value = info.adresse;
    document.getElementById("site_web").value = info.site_web;
    document.getElementById("statut").value = info.statut;
    document.getElementById("categorie").value = info.categorie_nom;
  }

  function initializeOffreEventListeners() {
    document
      .getElementById("paginationOffres")
      .addEventListener("click", (e) => {
        const target = e.target.closest("button");
        if (!target) return;

        if (target.hasAttribute("data-action")) {
          const action = target.getAttribute("data-action");
          if (action === "previous" && offrePagination.currentPage > 1) {
            changeOffrePage(offrePagination.currentPage - 1);
          } else if (
            action === "next" &&
            offrePagination.currentPage < offrePagination.totalPages
          ) {
            changeOffrePage(offrePagination.currentPage + 1);
          } else if (action === "page") {
            const page = parseInt(target.getAttribute("data-page"));
            changeOffrePage(page);
          }
        }
      });
  }

  async function loadOffresFromBackend(page = 1, limit = 10) {
    try {
      const params = new URLSearchParams({
        page: page.toString(),
        limit: limit.toString(),
      });

      const url = `${ROOT}public/Partenaire/getPartnerOffers?${params.toString()}`;
      const response = await fetch(url);
      const data = await response.json();

      if (data.status === "success") {
        updateOffreTable(data.data);
        offrePagination.totalItems = data.pagination.total;
        offrePagination.totalPages = data.pagination.total_pages;
        updateOffrePagination();
        updateOffrePaginationInfo(data.pagination.total);
      } else {
        console.error("Error loading offers:", data.message);
      }
    } catch (error) {
      console.error("Error loading offers:", error);
    }
  }

  function ajustPath(thumbnailPath) {
    let path = thumbnailPath;
    let trimmedPath = thumbnailPath.includes("public/")
      ? path.split("public/")[1]
      : path;
    return `${ROOT}public/${trimmedPath}`;
  }

  function updateOffreTable(offres) {
    const tableBody = document.getElementById("OffersTableBody");
    tableBody.innerHTML = offres
      .map(
        (offre) => `
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    ${offre.id}
                </td>
                <td class="px-6 py-4">${offre.type_offre}</td>
                <td class="px-6 py-4">${offre.valeur}</td>
                <td class="px-6 py-4">${offre.description}</td>
                <td class="px-6 py-4">${offre.date_debut}</td>
                <td class="px-6 py-4">${offre.date_fin}</td>
                <td class="px-6 py-4">${offre.is_special ? "Oui" : "Non"}</td>
                <td class="px-6 py-4">
                ${
                  offre.thumbnail_path
                    ? `<img src="${ajustPath(
                        offre.thumbnail_path
                      )}" alt="Thumbnail" class="w-16 h-16 object-cover rounded">`
                    : "/"
                }
                </td>
            </tr>
        `
      )
      .join("");

    updateOffrePaginationInfo(offrePagination.totalItems);
  }

  function updateOffrePagination() {
    const pagination = document.getElementById("paginationOffres");
    pagination.innerHTML = `
            <li>
                <button data-action="previous" class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 ${
                  offrePagination.currentPage === 1
                    ? "cursor-not-allowed opacity-50"
                    : ""
                }" ${offrePagination.currentPage === 1 ? "disabled" : ""}>
                    <span class="sr-only">Précédent</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </li>`;

    for (let i = 1; i <= offrePagination.totalPages; i++) {
      pagination.innerHTML += `
                <li>
                    <button data-action="page" data-page="${i}" class="flex items-center justify-center px-3 py-2 text-sm leading-tight ${
        offrePagination.currentPage === i
          ? "text-blue-600 bg-blue-50 border border-blue-300"
          : "text-gray-500 bg-white border border-gray-300"
      } hover:bg-gray-100">
                        ${i}
                    </button>
                </li>`;
    }

    pagination.innerHTML += `
            <li>
                <button data-action="next" class="flex items-center justify-center h-full py-1.5 px-3 text-gray-500 bg-white rounded-r-lg border border-gray-300 hover:bg-gray-100 ${
                  offrePagination.currentPage === offrePagination.totalPages
                    ? "cursor-not-allowed opacity-50"
                    : ""
                }" ${
      offrePagination.currentPage === offrePagination.totalPages
        ? "disabled"
        : ""
    }>
                    <span class="sr-only">Suivant</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </li>`;
  }

  function updateOffrePaginationInfo(totalItems) {
    const start = Math.min(
      (offrePagination.currentPage - 1) * offrePagination.itemsPerPage + 1,
      totalItems
    );
    const end = Math.min(
      offrePagination.currentPage * offrePagination.itemsPerPage,
      totalItems
    );

    document.getElementById("startIndexOffres").textContent = start;
    document.getElementById("endIndexOffres").textContent = end;
    document.getElementById("totalItemsOffres").textContent = totalItems;
  }

  function changeOffrePage(page) {
    if (page >= 1 && page <= offrePagination.totalPages) {
      offrePagination.currentPage = page;
      loadOffresFromBackend(page, offrePagination.itemsPerPage);
    }
  }
}
