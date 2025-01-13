{
  const ROOT = "http://localhost/TDWProject/";
  const tabs = document.querySelectorAll(".tab-link");
  const tabPanes = document.querySelectorAll(".tab-pane");

  const remisePagination = {
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

  initializeRemiseEventListeners();
  loadRemiseFromBackend(
    remisePagination.currentPage,
    remisePagination.itemsPerPage
  );

  function initializeRemiseEventListeners() {
    document
      .getElementById("addRemiseForm")
      .addEventListener("submit", async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);

        try {
          const response = await fetch(`${ROOT}public/Partenaire/addRemise`, {
            method: "POST",
            body: formData,
          });

          const data = await response.json();

          if (data.status === "success") {
            alert(data.message);
            loadRemiseFromBackend(
              remisePagination.currentPage,
              remisePagination.itemsPerPage
            );
            e.target.reset();
          } else {
            console.error("Error adding remise:", data.message);
            alert("Erreur: " + data.message);
          }
        } catch (error) {
          console.error("Error adding remise:", error);
          alert("Une erreur s'est produite lors de l'ajout de la remise.");
        }
      });

    document
      .getElementById("paginationRemise")
      .addEventListener("click", (e) => {
        const target = e.target.closest("button");
        if (!target) return;

        if (target.hasAttribute("data-action")) {
          const action = target.getAttribute("data-action");
          if (action === "previous" && remisePagination.currentPage > 1) {
            changeRemisePage(remisePagination.currentPage - 1);
          } else if (
            action === "next" &&
            remisePagination.currentPage < remisePagination.totalPages
          ) {
            changeRemisePage(remisePagination.currentPage + 1);
          } else if (action === "page") {
            const page = parseInt(target.getAttribute("data-page"));
            changeRemisePage(page);
          }
        }
      });
  }

  async function loadRemiseFromBackend(page = 1, limit = 10) {
    try {
      const params = new URLSearchParams({
        page: page.toString(),
        limit: limit.toString(),
      });

      const url = `${ROOT}public/Partenaire/getAllRemises?${params.toString()}`;

      const response = await fetch(url);
      const data = await response.json();

      if (data.status === "success") {
        updateRemiseTable(data.data);
        remisePagination.totalItems = data.pagination.total;
        remisePagination.totalPages = data.pagination.total_pages;
        updateRemisePagination();
        updateRemisePaginationInfo(data.pagination.total);
      } else {
        console.error("Error loading remise:", data.message);
      }
    } catch (error) {
      console.error("Error loading remise:", error);
    }
  }

  function updateRemiseTable(remises) {
    const tableBody = document.getElementById("remiseTableBody");
    tableBody.innerHTML = remises
      .map(
        (remise) => `
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td class="px-6 py-4">${remise.compte_membre_nom}</td>
                <td class="px-6 py-4">${remise.compte_membre_prenom}</td>
                <td class="px-6 py-4">${remise.offre_id}</td>
                <td class="px-6 py-4">${remise.date_benefice}</td>
            </tr>
        `
      )
      .join("");

    updateRemisePaginationInfo(remisePagination.totalItems);
    updateRemisePagination();
  }

  function updateRemisePagination() {
    const pagination = document.getElementById("paginationRemise");
    pagination.innerHTML = `
        <li>
            <button data-action="previous" class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 ${
              remisePagination.currentPage === 1
                ? "cursor-not-allowed opacity-50"
                : ""
            }" ${remisePagination.currentPage === 1 ? "disabled" : ""}>
                <span class="sr-only">Précédent</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </li>`;

    for (let i = 1; i <= remisePagination.totalPages; i++) {
      pagination.innerHTML += `
            <li>
                <button data-action="page" data-page="${i}" class="flex items-center justify-center px-3 py-2 text-sm leading-tight ${
        remisePagination.currentPage === i
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
              remisePagination.currentPage === remisePagination.totalPages
                ? "cursor-not-allowed opacity-50"
                : ""
            }" ${
      remisePagination.currentPage === remisePagination.totalPages
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

  function updateRemisePaginationInfo(totalItems) {
    const start = Math.min(
      (remisePagination.currentPage - 1) * remisePagination.itemsPerPage + 1,
      totalItems
    );
    const end = Math.min(
      remisePagination.currentPage * remisePagination.itemsPerPage,
      totalItems
    );

    document.getElementById("startIndexRemise").textContent = start;
    document.getElementById("endIndexRemise").textContent = end;
    document.getElementById("totalItemsRemise").textContent = totalItems;
  }

  function changeRemisePage(page) {
    if (page >= 1 && page <= remisePagination.totalPages) {
      remisePagination.currentPage = page;
      loadRemiseFromBackend(page, remisePagination.itemsPerPage);
    }
  }
}
