{
  const ROOT = "http://localhost/TDWProject/";
  const tabs = document.querySelectorAll(".tab-link");
  const tabPanes = document.querySelectorAll(".tab-pane");

  const partnerPagination = {
    currentPage: 1,
    itemsPerPage: 10,
    totalItems: 0,
    totalPages: 1,
  };

  const accountPagination = {
    currentPage: 1,
    itemsPerPage: 10,
    totalItems: 0,
    totalPages: 1,
  };

  const categoryPagination = {
    currentPage: 1,
    itemsPerPage: 10,
    totalItems: 0,
    totalPages: 1,
  };

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

  // -----------------------------------------------------------------------------------
  // Partie Gestion Partenaire
  // -----------------------------------------------------------------------------------

  initializeEventListeners();
  loadPartnersFromBackend(
    partnerPagination.currentPage,
    partnerPagination.itemsPerPage
  );
  populateCategoriesDropDown();

  function initializeEventListeners() {
    document.getElementById("filterButton").addEventListener("click", () => {
      const dropdown = document.getElementById("filterDropdown");
      dropdown.classList.toggle("hidden");
    });

    document.getElementById("simple-search").addEventListener("input", (e) => {
      const searchTerm = e.target.value;
      partnerPagination.currentPage = 1;
      loadPartnersFromBackend(
        partnerPagination.currentPage,
        partnerPagination.itemsPerPage,
        searchTerm
      );
    });

    document
      .getElementById("filterVille")
      .addEventListener("input", applyFilters);
    document
      .getElementById("filterCategorie")
      .addEventListener("input", applyFilters);

    document.getElementById("selectAll").addEventListener("change", (e) => {
      const checkboxes = document.querySelectorAll(
        'tbody input[type="checkbox"]'
      );
      checkboxes.forEach((checkbox) => (checkbox.checked = e.target.checked));
    });

    document
      .getElementById("deleteAllButton")
      .addEventListener("click", deleteSelected);

    document.getElementById("clearVille").addEventListener("click", () => {
      document.getElementById("filterVille").value = null;
      applyFilters();
    });

    document.getElementById("clearCategorie").addEventListener("click", () => {
      document.getElementById("filterCategorie").value = null;
      applyFilters();
    });

    document.getElementById("clearAllFilters").addEventListener("click", () => {
      document.getElementById("filterVille").value = null;
      document.getElementById("filterCategorie").value = null;
      applyFilters();
    });

    document
      .getElementById("btnAjouterPartenaire")
      .addEventListener("click", () => {
        console.log("Button clicked");
        const modal = document.getElementById("createPartnerModal");
        const form = modal.querySelector("form");
        form.reset();
        modal.classList.remove("hidden");
        modal.setAttribute("aria-hidden", "false");
        modal.inert = false; // Remove inert if using it
      });

    document
      .querySelector("#createPartnerModal form")
      .addEventListener("submit", async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const partenaireId = formData.get("partenaire_id");

        try {
          const url = partenaireId
            ? `${ROOT}admin/Admin/editPartnerInfos`
            : `${ROOT}admin/Admin/addPartner`;
          method = "POST";
          const response = await fetch(url, {
            method: method,
            body: formData,
          });

          const data = await response.json();

          if (data.status === "success") {
            alert(data.message);
            loadPartnersFromBackend(
              partnerPagination.currentPage,
              partnerPagination.itemsPerPage
            );
            e.target.reset();
            document
              .getElementById("createPartnerModal")
              .classList.add("hidden");
          } else {
            console.error("Error adding partner:", data.message);
            alert("Erreur: " + data.message);
          }
        } catch (error) {
          console.error("Error adding partner:", error);
          alert("Une erreur s'est produite lors de l'ajout du partenaire.");
        }
      });

    const closeButtons = document.querySelectorAll(
      '[data-modal-toggle="createPartnerModal"]'
    );
    closeButtons.forEach((button) => {
      button.addEventListener("click", () => {
        const modal = document.getElementById("createPartnerModal");
        if (modal) {
          modal.classList.add("hidden");
          modal.inert = true;
        }
      });
    });

    document
      .getElementById("partnersTableBody")
      .addEventListener("click", (e) => {
        const target = e.target;
        if (target.getAttribute("data-action") === "delete") {
          const partenaireId = target.getAttribute("data-id");
          deletePartner(partenaireId);
        } else if (target.getAttribute("data-action") === "edit") {
          const partenaireId = target.getAttribute("data-id");
          editPartner(partenaireId);
        }
      });

    document.getElementById("pagination").addEventListener("click", (e) => {
      const target = e.target.closest("button");
      if (!target) return;

      if (target.hasAttribute("data-action")) {
        const action = target.getAttribute("data-action");
        if (action === "previous" && partnerPagination.currentPage > 1) {
          changePage(partnerPagination.currentPage - 1);
        } else if (
          action === "next" &&
          partnerPagination.currentPage < partnerPagination.totalPages
        ) {
          changePage(partnerPagination.currentPage + 1);
        } else if (action === "page") {
          const page = parseInt(target.getAttribute("data-page"));
          changePage(page);
        }
      }
    });
  }

  function populateCategoriesDropDown() {
    const categorySelect = document.getElementById("categorie");
    const page = 1;
    const limit = 10;
    const url = `${ROOT}admin/Admin/getAllCategories?page=${page}&limit=${limit}`;

    fetch(url)
      .then((response) => {
        if (!response.ok) {
          throw new Error("Response was not ok");
        }
        return response.json();
      })
      .then((data) => {
        if (data.status === "success" && Array.isArray(data.data)) {
          categorySelect.innerHTML =
            '<option value="" selected disabled>Sélectionnez une catégorie</option>';

          data.data.forEach((category) => {
            const option = document.createElement("option");
            option.value = category.id;
            option.textContent = category.nom;
            option.classList.add(
              "bg-gray-100",
              "text-black",
              "py-2",
              "px-4",
              "text-sm"
            );
            categorySelect.appendChild(option);
          });

          categorySelect.addEventListener("change", function () {
            if (!this.value) {
              console.log("No category selected");
            } else {
              console.log("Selected category ID:", this.value);
            }
          });
        } else {
          console.error(
            "Error fetching categories:",
            data.message || "Invalid data format"
          );
          alert("Erreur lors du chargement des catégories");
        }
      })
      .catch((error) => {
        console.error("Error fetching categories:", error);
        alert("Une erreur s'est produite lors du chargement des catégories.");
      });
  }

  function deletePartner(partenaireId) {
    if (confirm("Êtes-vous sûr de vouloir supprimer ce partenaire ?")) {
      fetch(`${ROOT}admin/Admin/deletePartners`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ ids: [partenaireId] }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status === "success") {
            alert(data.message);
            loadPartnersFromBackend(
              partnerPagination.currentPage,
              partnerPagination.itemsPerPage
            );
          } else {
            console.error("Error deleting partner:", data.message);
            alert("Erreur: " + data.message);
          }
        })
        .catch((error) => {
          console.error("Error deleting partner:", error);
          alert(
            "Une erreur s'est produite lors de la suppression du partenaire."
          );
        });
    }
  }

  async function editPartner(partenaireId) {
    try {
      const response = await fetch(
        `${ROOT}admin/Admin/getPartnerDetails?partenaire_id=${partenaireId}`
      );
      const data = await response.json();

      if (data.status === "success") {
        const partner = data.data[0];

        const form = document.querySelector("#createPartnerModal form");
        form.querySelector('input[name="nom"]').value = partner.nom;
        form.querySelector('input[name="ville"]').value = partner.ville;
        form.querySelector('input[name="email"]').value = partner.email;
        form.querySelector('input[name="numero_de_telephone"]').value =
          partner.numero_de_telephone;
        form.querySelector('input[name="adresse"]').value = partner.adresse;
        form.querySelector('input[name="site_web"]').value = partner.site_web;
        form.querySelector('select[name="categorie_id"]').value =
          partner.categorie_id;

        let partnerIdInput = form.querySelector('input[name="partenaire_id"]');
        if (!partnerIdInput) {
          partnerIdInput = document.createElement("input");
          partnerIdInput.type = "hidden";
          partnerIdInput.name = "partenaire_id";
          form.appendChild(partnerIdInput);
        }
        partnerIdInput.value = partenaireId;

        const modal = document.getElementById("createPartnerModal");
        modal.classList.remove("hidden");
      } else {
        console.error("Error fetching partner data:", data.message);
        alert("Erreur: " + data.message);
      }
    } catch (error) {
      console.error("Error fetching partner data:", error);
      alert(
        "Une erreur s'est produite lors de la récupération des données du partenaire."
      );
    }
  }

  function deleteSelected() {
    const selectedCheckboxes = document.querySelectorAll(
      'tbody input[type="checkbox"]:checked'
    );
    if (selectedCheckboxes.length === 0) {
      alert("Veuillez sélectionner au moins un partenaire à supprimer");
      return;
    }

    if (
      confirm(
        `Êtes-vous sûr de vouloir supprimer ${selectedCheckboxes.length} partenaire(s) ?`
      )
    ) {
      const selectedIds = Array.from(selectedCheckboxes).map((checkbox) =>
        parseInt(checkbox.getAttribute("data-id"))
      );

      fetch(`${ROOT}admin/Admin/deletePartners`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ ids: selectedIds }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status === "success") {
            loadPartnersFromBackend(
              partnerPagination.currentPage,
              partnerPagination.itemsPerPage
            );
          } else {
            console.error("Error deleting partners:", data.message);
          }
        })
        .catch((error) => {
          console.error("Error deleting partners:", error);
        });
    }
  }

  function applyFilters() {
    const villeFilter = document.getElementById("filterVille").value;
    const categorieFilter = document.getElementById("filterCategorie").value;
    partnerPagination.currentPage = 1;
    loadPartnersFromBackend(
      partnerPagination.currentPage,
      partnerPagination.itemsPerPage,
      null,
      villeFilter,
      categorieFilter
    );
  }

  function updateTable(partners) {
    const tableBody = document.getElementById("partnersTableBody");
    tableBody.innerHTML = partners
      .map(
        (partner) => `
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
            <td class="px-6 py-4">
                <input type="checkbox" class="w-4 h-4" data-id="${partner.id}">
            </td>
            <td class="px-6 py-4">${partner.id}</td>
            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                ${partner.nom}
            </td>
            <td class="px-6 py-4">${partner.ville}</td>
            <td class="px-6 py-4">${partner.categorie_id}</td>
            <td class="px-6 py-4">${partner.email}</td>
            <td class="px-6 py-4 text-right">
                <button data-action="edit" data-id="${partner.id}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline mr-3">
                    Modifier
                </button>
                <button data-action="delete" data-id="${partner.id}" class="font-medium text-red-600 dark:text-red-500 hover:underline">
                    Supprimer
                </button>
            </td>
        </tr>
    `
      )
      .join("");

    updatePaginationInfo(partnerPagination.totalItems);
  }

  function updatePaginationInfo(totalItems) {
    const start = Math.min(
      (partnerPagination.currentPage - 1) * partnerPagination.itemsPerPage + 1,
      totalItems
    );
    const end = Math.min(
      partnerPagination.currentPage * partnerPagination.itemsPerPage,
      totalItems
    );

    document.getElementById("startIndex").textContent = start;
    document.getElementById("endIndex").textContent = end;
    document.getElementById("totalItems").textContent = totalItems;
  }

  function updatePagination() {
    const pagination = document.getElementById("pagination");
    pagination.innerHTML = `
        <li>
            <button data-action="previous" class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 ${
              partnerPagination.currentPage === 1
                ? "cursor-not-allowed opacity-50"
                : ""
            }" ${partnerPagination.currentPage === 1 ? "disabled" : ""}>
                <span class="sr-only">Précédent</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            </button>
            </li>`;

    for (let i = 1; i <= partnerPagination.totalPages; i++) {
      pagination.innerHTML += `
            <li>
                <button data-action="page" data-page="${i}" class="flex items-center justify-center px-3 py-2 text-sm leading-tight ${
        partnerPagination.currentPage === i
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
              partnerPagination.currentPage === partnerPagination.totalPages
                ? "cursor-not-allowed opacity-50"
                : ""
            }" ${
      partnerPagination.currentPage === partnerPagination.totalPages
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

  function changePage(page) {
    if (page >= 1 && page <= partnerPagination.totalPages) {
      partnerPagination.currentPage = page;
      loadPartnersFromBackend(page, partnerPagination.itemsPerPage);
    }
  }

  async function loadPartnersFromBackend(
    page = 1,
    limit = 10,
    nom = null,
    ville = null,
    categorie_id = null
  ) {
    try {
      const params = new URLSearchParams({
        page: page.toString(),
        limit: limit.toString(),
      });

      if (nom && nom !== "null" && nom.trim() !== "") {
        params.append("nom", nom);
      }

      if (ville && ville !== "null" && ville.trim() !== "") {
        params.append("ville", ville);
      }

      if (
        categorie_id &&
        categorie_id !== "null" &&
        categorie_id.trim() !== ""
      ) {
        params.append("categorie_id", categorie_id);
      }

      const url = `${ROOT}admin/Admin/getAllPartners?${params.toString()}`;

      const response = await fetch(url);
      const data = await response.json();

      if (data.status === "success") {
        updateTable(data.data);
        partnerPagination.totalItems = data.pagination.total;
        partnerPagination.totalPages = data.pagination.total_pages;
        updatePagination();
        updatePaginationInfo(data.pagination.total);
      } else {
        console.error("Error loading partners:", data.message);
      }
    } catch (error) {
      console.error("Error loading partners:", error);
    }
  }

  // -----------------------------------------------------------------------------------
  // Partie Compte Partenaire
  // -----------------------------------------------------------------------------------

  initializeComptePartenaireEventListeners();
  loadComptePartenaireFromBackend(
    accountPagination.currentPage,
    accountPagination.itemsPerPage
  );

  function initializeComptePartenaireEventListeners() {
    document.getElementById("btnCreerCompte").addEventListener("click", () => {
      const modal = document.getElementById("createCompteModal");
      const form = modal.querySelector("form");
      form.reset();
      modal.classList.remove("hidden");
    });

    document
      .querySelector("#createCompteModal form")
      .addEventListener("submit", async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);

        try {
          const response = await fetch(
            `${ROOT}admin/Admin/createPartnerAccount`,
            {
              method: "POST",
              body: formData,
            }
          );

          const data = await response.json();

          if (data.status === "success") {
            alert(data.message);
            loadComptePartenaireFromBackend(
              accountPagination.currentPage,
              accountPagination.itemsPerPage
            );
            e.target.reset();
            document
              .getElementById("createCompteModal")
              .classList.add("hidden");
          } else {
            console.error("Error creating account:", data.message);
            alert("Erreur: " + data.message);
          }
        } catch (error) {
          console.error("Error creating account:", error);
          alert(
            "Une erreur s'est produite lors de la création du compte partenaire."
          );
        }
      });

    const closeCompteButtons = document.querySelectorAll(
      '[data-modal-toggle="createCompteModal"]'
    );
    closeCompteButtons.forEach((button) => {
      button.addEventListener("click", () => {
        const modal = document.getElementById("createCompteModal");
        if (modal) {
          modal.classList.add("hidden");
        }
      });
    });

    document
      .getElementById("comptePartenaireTableBody")
      .addEventListener("click", (e) => {
        const target = e.target;
        if (target.getAttribute("data-action") === "delete") {
          const compteId = target.getAttribute("data-id");
          deleteComptePartenaire(compteId);
        }
      });

    document
      .getElementById("comptePartenaireTableBody")
      .addEventListener("click", (e) => {
        const target = e.target;
        if (target.getAttribute("data-action") === "edit") {
          const compteId = target.getAttribute("data-id");
          editComptePartenaire(compteId);
        }
      });

    document
      .getElementById("paginationComptePartenaire")
      .addEventListener("click", (e) => {
        const target = e.target.closest("button");
        if (!target) return;

        if (target.hasAttribute("data-action")) {
          const action = target.getAttribute("data-action");
          if (action === "previous" && accountPagination.currentPage > 1) {
            changeComptePartenairePage(accountPagination.currentPage - 1);
          } else if (
            action === "next" &&
            accountPagination.currentPage < accountPagination.totalPages
          ) {
            changeComptePartenairePage(accountPagination.currentPage + 1);
          } else if (action === "page") {
            const page = parseInt(target.getAttribute("data-page"));
            changeComptePartenairePage(page);
          }
        }
      });
  }

  async function loadComptePartenaireFromBackend(page = 1, limit = 10) {
    try {
      const params = new URLSearchParams({
        page: page.toString(),
        limit: limit.toString(),
      });

      const url = `${ROOT}admin/Admin/getAllComptesPartenaires?${params.toString()}`;

      const response = await fetch(url);
      const data = await response.json();

      if (data.status === "success") {
        updateComptePartenaireTable(data.data);
        accountPagination.totalItems = data.pagination.total;
        accountPagination.totalPages = data.pagination.total_pages;
        updateComptePartenairePagination();
        updateComptePartenairePaginationInfo(data.pagination.total);
      } else {
        console.error("Error loading compte partenaire:", data.message);
      }
    } catch (error) {
      console.error("Error loading compte partenaire:", error);
    }
  }

  function updateComptePartenaireTable(comptes) {
    const tableBody = document.getElementById("comptePartenaireTableBody");
    tableBody.innerHTML = comptes
      .map(
        (compte) => `
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                ${compte.partenaire_id}
            </td>
            <td class="px-6 py-4">${compte.email}</td>
            <td class="px-6 py-4">${compte.statut}</td>
            <td class="px-6 py-4">${compte.created_by}</td>
            <td class="px-6 py-4 text-right">
                <button data-action="edit" data-id="${compte.id}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline mr-3">
                    Modifier
                </button>
                <button data-action="delete" data-id="${compte.id}" class="font-medium text-red-600 dark:text-red-500 hover:underline">
                    Supprimer
                </button>
            </td>
        </tr>
    `
      )
      .join("");

    updateComptePartenairePaginationInfo(accountPagination.totalItems);
    updateComptePartenairePagination();
  }

  function updateComptePartenairePagination() {
    const pagination = document.getElementById("paginationComptePartenaire");
    pagination.innerHTML = `
        <li>
            <button data-action="previous" class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 ${
              accountPagination.currentPage === 1
                ? "cursor-not-allowed opacity-50"
                : ""
            }" ${accountPagination.currentPage === 1 ? "disabled" : ""}>
                <span class="sr-only">Précédent</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </li>`;

    for (let i = 1; i <= accountPagination.totalPages; i++) {
      pagination.innerHTML += `
            <li>
                <button data-action="page" data-page="${i}" class="flex items-center justify-center px-3 py-2 text-sm leading-tight ${
        accountPagination.currentPage === i
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
              accountPagination.currentPage === accountPagination.totalPages
                ? "cursor-not-allowed opacity-50"
                : ""
            }" ${
      accountPagination.currentPage === accountPagination.totalPages
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

  function updateComptePartenairePaginationInfo(totalItems) {
    const start = Math.min(
      (accountPagination.currentPage - 1) * accountPagination.itemsPerPage + 1,
      totalItems
    );
    const end = Math.min(
      accountPagination.currentPage * accountPagination.itemsPerPage,
      totalItems
    );

    document.getElementById("startIndexCompte").textContent = start;
    document.getElementById("endIndexCompte").textContent = end;
    document.getElementById("totalItemsCompte").textContent = totalItems;
  }

  function changeComptePartenairePage(page) {
    if (page >= 1 && page <= accountPagination.totalPages) {
      accountPagination.currentPage = page;
      loadComptePartenaireFromBackend(page, accountPagination.itemsPerPage);
    }
  }

  async function deleteComptePartenaire(compteId) {
    if (confirm("Êtes-vous sûr de vouloir supprimer ce compte partenaire ?")) {
      try {
        formData = new FormData();
        formData.append("compte_partenaire_id", compteId);
        const response = await fetch(
          `${ROOT}admin/Admin/deletePartnerAccount`,
          {
            method: "POST",
            body: formData,
          }
        );

        const data = await response.json();

        if (data.status === "success") {
          alert(data.message);
          loadComptePartenaireFromBackend(
            accountPagination.currentPage,
            accountPagination.itemsPerPage
          );
        } else {
          console.error("Error deleting compte partenaire:", data.message);
          alert("Erreur: " + data.message);
        }
      } catch (error) {
        console.error("Error deleting compte partenaire:", error);
        alert(
          "Une erreur s'est produite lors de la suppression du compte partenaire."
        );
      }
    }
  }

  // category section

  initializeCategoryEventListeners();
  loadCategoriesFromBackend(
    categoryPagination.currentPage,
    categoryPagination.itemsPerPage
  );

  function initializeCategoryEventListeners() {
    document
      .getElementById("btnCreerCategorie")
      .addEventListener("click", () => {
        const modal = document.getElementById("createCategoryModal");
        const form = modal.querySelector("form");
        form.reset();
        modal.classList.remove("hidden");
      });

    document
      .querySelector("#createCategoryModal form")
      .addEventListener("submit", async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);

        try {
          const url = `${ROOT}admin/Admin/addCategory`;
          const response = await fetch(url, {
            method: "POST",
            body: formData,
          });

          const data = await response.json();

          if (data.status === "success") {
            alert(data.message);
            loadCategoriesFromBackend(
              categoryPagination.currentPage,
              categoryPagination.itemsPerPage
            );
            e.target.reset();
            document
              .getElementById("createCategoryModal")
              .classList.add("hidden");
          } else {
            console.error("Error managing category:", data.message);
            alert("Erreur: " + data.message);
          }
        } catch (error) {
          console.error("Error managing category:", error);
          alert(
            "Une erreur s'est produite lors de la gestion de la catégorie."
          );
        }
      });

    const closeCategoryButtons = document.querySelectorAll(
      '[data-modal-toggle="createCategoryModal"]'
    );
    closeCategoryButtons.forEach((button) => {
      button.addEventListener("click", () => {
        const modal = document.getElementById("createCategoryModal");
        if (modal) {
          modal.classList.add("hidden");
        }
      });
    });

    document
      .getElementById("categoryTableBody")
      .addEventListener("click", (e) => {
        const target = e.target;
        if (target.getAttribute("data-action") === "delete") {
          const categoryId = target.getAttribute("data-id");
          deleteCategory(categoryId);
        }
      });

    document
      .getElementById("paginationCategory")
      .addEventListener("click", (e) => {
        const target = e.target.closest("button");
        if (!target) return;

        if (target.hasAttribute("data-action")) {
          const action = target.getAttribute("data-action");
          if (action === "previous" && categoryPagination.currentPage > 1) {
            changeCategoryPage(categoryPagination.currentPage - 1);
          } else if (
            action === "next" &&
            categoryPagination.currentPage < categoryPagination.totalPages
          ) {
            changeCategoryPage(categoryPagination.currentPage + 1);
          } else if (action === "page") {
            const page = parseInt(target.getAttribute("data-page"));
            changeCategoryPage(page);
          }
        }
      });
  }

  async function loadCategoriesFromBackend(page = 1, limit = 10) {
    try {
      const params = new URLSearchParams({
        page: page.toString(),
        limit: limit.toString(),
      });

      const url = `${ROOT}admin/Admin/getAllCategories?${params.toString()}`;
      const response = await fetch(url);
      const data = await response.json();

      if (data.status === "success") {
        updateCategoryTable(data.data);
        categoryPagination.totalItems = data.pagination.total;
        categoryPagination.totalPages = data.pagination.total_pages;
        updateCategoryPagination();
        updateCategoryPaginationInfo(data.pagination.total);
      } else {
        console.error("Error loading categories:", data.message);
      }
    } catch (error) {
      console.error("Error loading categories:", error);
    }
  }

  function updateCategoryTable(categories) {
    const tableBody = document.getElementById("categoryTableBody");
    tableBody.innerHTML = categories
      .map(
        (category) => `
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                ${category.id}
            </td>
            <td class="px-6 py-4">${category.nom}</td>
            <td class="px-6 py-4">
                <button data-action="delete" data-id="${category.id}" class="font-medium text-red-600 dark:text-red-500 hover:underline">
                    Supprimer
                </button>
            </td>
        </tr>
    `
      )
      .join("");

    updateCategoryPaginationInfo(categoryPagination.totalItems);
  }

  function updateCategoryPagination() {
    const pagination = document.getElementById("paginationCategory");
    pagination.innerHTML = `
        <li>
            <button data-action="previous" class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 ${
              categoryPagination.currentPage === 1
                ? "cursor-not-allowed opacity-50"
                : ""
            }" ${categoryPagination.currentPage === 1 ? "disabled" : ""}>
                <span class="sr-only">Précédent</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </li>`;

    for (let i = 1; i <= categoryPagination.totalPages; i++) {
      pagination.innerHTML += `
            <li>
                <button data-action="page" data-page="${i}" class="flex items-center justify-center px-3 py-2 text-sm leading-tight ${
        categoryPagination.currentPage === i
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
              categoryPagination.currentPage === categoryPagination.totalPages
                ? "cursor-not-allowed opacity-50"
                : ""
            }" ${
      categoryPagination.currentPage === categoryPagination.totalPages
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

  function updateCategoryPaginationInfo(totalItems) {
    const start = Math.min(
      (categoryPagination.currentPage - 1) * categoryPagination.itemsPerPage +
        1,
      totalItems
    );
    const end = Math.min(
      categoryPagination.currentPage * categoryPagination.itemsPerPage,
      totalItems
    );

    document.getElementById("startIndexCategory").textContent = start;
    document.getElementById("endIndexCategory").textContent = end;
    document.getElementById("totalItemsCategory").textContent = totalItems;
  }

  function changeCategoryPage(page) {
    if (page >= 1 && page <= categoryPagination.totalPages) {
      categoryPagination.currentPage = page;
      loadCategoriesFromBackend(page, categoryPagination.itemsPerPage);
    }
  }

  async function deleteCategory(categoryId) {
    if (confirm("Êtes-vous sûr de vouloir supprimer cette catégorie ?")) {
      try {
        const formData = new FormData();
        formData.append("category_id", categoryId);
        const response = await fetch(`${ROOT}admin/Admin/deleteCategory`, {
          method: "POST",
          body: formData,
        });

        const data = await response.json();

        if (data.status === "success") {
          alert(data.message);
          loadCategoriesFromBackend(
            categoryPagination.currentPage,
            categoryPagination.itemsPerPage
          );
        } else {
          console.error("Error deleting category:", data.message);
          alert("Erreur: " + data.message);
        }
      } catch (error) {
        console.error("Error deleting category:", error);
        alert(
          "Une erreur s'est produite lors de la suppression de la catégorie."
        );
      }
    }
  }

  // offres section
  const offrePagination = {
    currentPage: 1,
    itemsPerPage: 10,
    totalItems: 0,
    totalPages: 1,
  };

  initializeOffreEventListeners();
  loadOffresFromBackend(
    offrePagination.currentPage,
    offrePagination.itemsPerPage
  );

  function initializeOffreEventListeners() {
    document.getElementById("btnCreerOffre").addEventListener("click", () => {
      const modal = document.getElementById("createOffresModal");
      const form = modal.querySelector("form");
      form.reset();
      modal.classList.remove("hidden");
      modal.classList.remove("area-hidden");

      const thumbnailSection = document.getElementById("thumbnailSection");
      thumbnailSection.classList.add("hidden");
    });

    document.getElementById("is_special").addEventListener("change", (e) => {
      const thumbnailSection = document.getElementById("thumbnailSection");
      if (e.target.checked) {
        thumbnailSection.classList.remove("hidden");
      } else {
        thumbnailSection.classList.add("hidden");
      }
    });

    document
      .querySelector("#createOffresModal form")
      .addEventListener("submit", async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);

        if (!formData.get("is_special")) {
          formData.set("is_special", "0");
        } else {
          formData.set("is_special", "1");
        }

        try {
          const url = `${ROOT}admin/Admin/addPartnerOffer`;
          const response = await fetch(url, {
            method: "POST",
            body: formData,
          });

          const data = await response.json();

          if (data.status === "success") {
            alert(data.message);
            loadOffresFromBackend(
              offrePagination.currentPage,
              offrePagination.itemsPerPage
            );
            e.target.reset();
            document
              .getElementById("createOffresModal")
              .classList.add("hidden");
          } else {
            console.error("Error managing offer:", data.message);
            alert("Erreur: " + data.message);
          }
        } catch (error) {
          console.error("Error managing offer:", error);
          alert("Une erreur s'est produite lors de la gestion de l'offre.");
        }
      });

    const closeOffreButtons = document.querySelectorAll(
      '[data-modal-toggle="createOffresModal"]'
    );
    closeOffreButtons.forEach((button) => {
      button.addEventListener("click", () => {
        const modal = document.getElementById("createOffresModal");
        if (modal) {
          modal.classList.add("hidden");
        }
      });
    });

    document
      .getElementById("OffersTableBody")
      .addEventListener("click", (e) => {
        const target = e.target;
        if (target.getAttribute("data-action") === "delete") {
          const offreId = target.getAttribute("data-id");
          deleteOffre(offreId);
        }
      });

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

      const url = `${ROOT}admin/Admin/getAllOffers?${params.toString()}`;
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
            <td class="px-6 py-4">${offre.partenaire_nom}</td>
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
            <td class="px-6 py-4">
                <button data-action="delete" data-id="${
                  offre.id
                }" class="font-medium text-red-600 dark:text-red-500 hover:underline">
                    Supprimer
                </button>
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

  async function deleteOffre(offreId) {
    if (confirm("Êtes-vous sûr de vouloir supprimer cette offre ?")) {
      try {
        const formData = new FormData();
        formData.append("offre_id", offreId);
        const response = await fetch(`${ROOT}admin/Admin/deleteOffre`, {
          method: "POST",
          body: formData,
        });

        const data = await response.json();

        if (data.status === "success") {
          alert(data.message);
          loadOffresFromBackend(
            offrePagination.currentPage,
            offrePagination.itemsPerPage
          );
        } else {
          console.error("Error deleting offer:", data.message);
          alert("Erreur: " + data.message);
        }
      } catch (error) {
        console.error("Error deleting offer:", error);
        alert("Une erreur s'est produite lors de la suppression de l'offre.");
      }
    }
  }
}
