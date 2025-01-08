{
  const ROOT = "http://localhost/TDWProject/";
  const tabs = document.querySelectorAll(".tab-link");
  const tabPanes = document.querySelectorAll(".tab-pane");

  const accountPagination = {
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

  // gestion compte admin

  initializeCompteAdminEventListeners();
  loadCompteAdminFromBackend(
    accountPagination.currentPage,
    accountPagination.itemsPerPage
  );

  function initializeCompteAdminEventListeners() {
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
            `${ROOT}admin/Admin/createAdminAccount`,
            {
              method: "POST",
              body: formData,
            }
          );

          const data = await response.json();

          if (data.status === "success") {
            alert(data.message);
            loadCompteAdminFromBackend(
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
            "Une erreur s'est produite lors de la création du compte admin."
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
      .getElementById("compteAdminTableBody")
      .addEventListener("click", (e) => {
        const target = e.target;
        if (target.getAttribute("data-action") === "delete") {
          const compteId = target.getAttribute("data-id");
          deleteCompteAdmin(compteId);
        }
      });

    document
      .getElementById("paginationCompteAdmin")
      .addEventListener("click", (e) => {
        const target = e.target.closest("button");
        if (!target) return;

        if (target.hasAttribute("data-action")) {
          const action = target.getAttribute("data-action");
          if (action === "previous" && accountPagination.currentPage > 1) {
            changeCompteAdminPage(accountPagination.currentPage - 1);
          } else if (
            action === "next" &&
            accountPagination.currentPage < accountPagination.totalPages
          ) {
            changeCompteAdminPage(accountPagination.currentPage + 1);
          } else if (action === "page") {
            const page = parseInt(target.getAttribute("data-page"));
            changeCompteAdminPage(page);
          }
        }
      });
  }

  async function loadCompteAdminFromBackend(page = 1, limit = 10) {
    try {
      const params = new URLSearchParams({
        page: page.toString(),
        limit: limit.toString(),
      });

      const url = `${ROOT}admin/Admin/getAllComptesAdmin?${params.toString()}`;

      const response = await fetch(url);
      const data = await response.json();

      if (data.status === "success") {
        updateCompteAdminTable(data.data);
        accountPagination.totalItems = data.pagination.total;
        accountPagination.totalPages = data.pagination.total_pages;
        updateCompteAdminPagination();
        updateCompteAdminPaginationInfo(data.pagination.total);
      } else {
        console.error("Error loading compte admin:", data.message);
      }
    } catch (error) {
      console.error("Error loading compte admin:", error);
    }
  }

  function updateCompteAdminTable(comptes) {
    const tableBody = document.getElementById("compteAdminTableBody");
    tableBody.innerHTML = comptes
      .map(
        (compte) => `
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                ${compte.id}
            </td>
            <td class="px-6 py-4">${compte.email}</td>
            <td class="px-6 py-4">${compte.nom_user}</td>
            <td class="px-6 py-4">${compte.role}</td>
            <td class="px-6 py-4">${compte.created_by}</td>
            <td class="px-6 py-4 ">
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

    updateCompteAdminPaginationInfo(accountPagination.totalItems);
    updateCompteAdminPagination();
  }

  function updateCompteAdminPagination() {
    const pagination = document.getElementById("paginationCompteAdmin");
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

  function updateCompteAdminPaginationInfo(totalItems) {
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

  function changeCompteAdminPage(page) {
    if (page >= 1 && page <= accountPagination.totalPages) {
      accountPagination.currentPage = page;
      loadCompteAdminFromBackend(page, accountPagination.itemsPerPage);
    }
  }

  async function deleteCompteAdmin(compteId) {
    if (confirm("Êtes-vous sûr de vouloir supprimer ce compte admin ?")) {
      try {
        formData = new FormData();
        formData.append("admin_id", compteId);
        const response = await fetch(`${ROOT}admin/Admin/deleteAdminAccount`, {
          method: "POST",
          body: formData,
        });

        const data = await response.json();

        if (data.status === "success") {
          alert(data.message);
          loadCompteAdminFromBackend(
            accountPagination.currentPage,
            accountPagination.itemsPerPage
          );
        } else {
          console.error("Error deleting compte admin:", data.message);
          alert("Erreur: " + data.message);
        }
      } catch (error) {
        console.error("Error deleting compte admin:", error);
        alert(
          "Une erreur s'est produite lors de la suppression du compte admin."
        );
      }
    }
  }
}
