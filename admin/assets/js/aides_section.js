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

  const typeAidesPagination = {
    currentPage: 1,
    itemsPerPage: 10,
    totalItems: 0,
    totalPages: 1,
  };

  initializeTypeAidesEventListeners();
  loadTypeAidesFromBackend(
    typeAidesPagination.currentPage,
    typeAidesPagination.itemsPerPage
  );

  function initializeTypeAidesEventListeners() {
    const dossierRequisTextarea = document.getElementById("dossier_requis");

    dossierRequisTextarea.addEventListener("input", function (e) {
      const lines = this.value.split("\n");
      const formattedLines = lines.map((line) => {
        if (!line.trim().startsWith("☐") && line.trim() !== "") {
          return "☐ " + line.trim();
        }
        return line;
      });

      const newContent = formattedLines.join("\n");
      if (this.value !== newContent) {
        this.value = newContent;
      }
    });

    dossierRequisTextarea.addEventListener("paste", function (e) {
      e.preventDefault();
      const pastedText = (e.clipboardData || window.clipboardData).getData(
        "text"
      );
      const lines = pastedText.split("\n");
      const formattedLines = lines.map((line) => {
        if (!line.trim().startsWith("☐") && line.trim() !== "") {
          return "☐ " + line.trim();
        }
        return line;
      });

      const startPos = this.selectionStart;
      const endPos = this.selectionEnd;
      const textBefore = this.value.substring(0, startPos);
      const textAfter = this.value.substring(endPos);

      this.value = textBefore + formattedLines.join("\n") + textAfter;

      const newPos = startPos + formattedLines.join("\n").length;
      this.setSelectionRange(newPos, newPos);
    });

    document
      .getElementById("btnCreerTypeAide")
      .addEventListener("click", () => {
        const modal = document.getElementById("createTypeAidesModal");
        const form = modal.querySelector("form");
        form.reset();
        modal.classList.remove("hidden");
        modal.removeAttribute("aria-hidden");
      });

    document
      .querySelector("#createTypeAidesModal form")
      .addEventListener("submit", async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);

        // Get values from all fields
        const label = formData.get("label");
        const dossierRequis = formData.get("dossier_requis");
        const description = formData.get("description");

        // Validate required fields
        if (!label || !dossierRequis) {
          alert("Veuillez remplir tous les champs requis");
          return;
        }

        try {
          const url = `${ROOT}admin/Admin/addTypeAide`;
          const response = await fetch(url, {
            method: "POST",
            body: formData,
          });

          const data = await response.json();

          if (data.status === "success") {
            alert(data.message);
            loadTypeAidesFromBackend(
              typeAidesPagination.currentPage,
              typeAidesPagination.itemsPerPage
            );
            e.target.reset();
            document
              .getElementById("createTypeAidesModal")
              .classList.add("hidden");
          } else {
            console.error("Error managing type aide:", data.message);
            alert("Erreur: " + data.message);
          }
        } catch (error) {
          console.error("Error managing type aide:", error);
          alert("Une erreur s'est produite lors de la gestion du type d'aide.");
        }
      });

    const closeTypeAidesButtons = document.querySelectorAll(
      '[data-modal-toggle="createTypeAidesModal"]'
    );
    closeTypeAidesButtons.forEach((button) => {
      button.addEventListener("click", () => {
        const modal = document.getElementById("createTypeAidesModal");
        if (modal) {
          modal.classList.add("hidden");
        }
      });
    });

    document
      .getElementById("typeAidesTableBody")
      .addEventListener("click", (e) => {
        const target = e.target;
        if (target.getAttribute("data-action") === "delete") {
          const typeAideId = target.getAttribute("data-id");
          deleteTypeAide(typeAideId);
        }
      });

    document
      .getElementById("paginationTypeAides")
      .addEventListener("click", (e) => {
        const target = e.target.closest("button");
        if (!target) return;

        if (target.hasAttribute("data-action")) {
          const action = target.getAttribute("data-action");
          if (action === "previous" && typeAidesPagination.currentPage > 1) {
            changeTypeAidePage(typeAidesPagination.currentPage - 1);
          } else if (
            action === "next" &&
            typeAidesPagination.currentPage < typeAidesPagination.totalPages
          ) {
            changeTypeAidePage(typeAidesPagination.currentPage + 1);
          } else if (action === "page") {
            const page = parseInt(target.getAttribute("data-page"));
            changeTypeAidePage(page);
          }
        }
      });
  }

  async function loadTypeAidesFromBackend(page = 1, limit = 10) {
    try {
      const params = new URLSearchParams({
        page: page.toString(),
        limit: limit.toString(),
      });

      const url = `${ROOT}admin/Admin/getAllTypeAide?${params.toString()}`;
      const response = await fetch(url);
      const data = await response.json();

      if (data.status === "success") {
        updateTypeAidesTable(data.data);
        typeAidesPagination.totalItems = data.pagination.total;
        typeAidesPagination.totalPages = data.pagination.total_pages;
        updateTypeAidesPagination();
        updateTypeAidesPaginationInfo(data.pagination.total);
      } else {
        console.error("Error loading type aides:", data.message);
      }
    } catch (error) {
      console.error("Error loading type aides:", error);
    }
  }

  function updateTypeAidesTable(typeAides) {
    const tableBody = document.getElementById("typeAidesTableBody");
    tableBody.innerHTML = typeAides
      .map(
        (typeAide) => `
              <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                  <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                      ${typeAide.id}
                  </td>
                  <td class="px-6 py-4">${typeAide.label}</td>
                  <td class="px-6 py-4">${typeAide.dossier_requis}</td>
                  <td class="px-6 py-4">
                      <button data-action="delete" data-id="${typeAide.id}" class="font-medium text-red-600 dark:text-red-500 hover:underline">
                          Supprimer
                      </button>
                  </td>
              </tr>
          `
      )
      .join("");

    updateTypeAidesPaginationInfo(typeAidesPagination.totalItems);
  }

  function updateTypeAidesPagination() {
    const pagination = document.getElementById("paginationTypeAides");
    pagination.innerHTML = `
              <li>
                  <button data-action="previous" class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 ${
                    typeAidesPagination.currentPage === 1
                      ? "cursor-not-allowed opacity-50"
                      : ""
                  }" ${typeAidesPagination.currentPage === 1 ? "disabled" : ""}>
                      <span class="sr-only">Précédent</span>
                      <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                      </svg>
                  </button>
              </li>`;

    for (let i = 1; i <= typeAidesPagination.totalPages; i++) {
      pagination.innerHTML += `
                  <li>
                      <button data-action="page" data-page="${i}" class="flex items-center justify-center px-3 py-2 text-sm leading-tight ${
        typeAidesPagination.currentPage === i
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
                    typeAidesPagination.currentPage ===
                    typeAidesPagination.totalPages
                      ? "cursor-not-allowed opacity-50"
                      : ""
                  }" ${
      typeAidesPagination.currentPage === typeAidesPagination.totalPages
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

  function updateTypeAidesPaginationInfo(totalItems) {
    const start = Math.min(
      (typeAidesPagination.currentPage - 1) * typeAidesPagination.itemsPerPage +
        1,
      totalItems
    );
    const end = Math.min(
      typeAidesPagination.currentPage * typeAidesPagination.itemsPerPage,
      totalItems
    );

    document.getElementById("startIndexTypeAides").textContent = start;
    document.getElementById("endIndexTypeAides").textContent = end;
    document.getElementById("totalItemsTypeAides").textContent = totalItems;
  }

  function changeTypeAidePage(page) {
    if (page >= 1 && page <= typeAidesPagination.totalPages) {
      typeAidesPagination.currentPage = page;
      loadTypeAidesFromBackend(page, typeAidesPagination.itemsPerPage);
    }
  }

  async function deleteTypeAide(typeAideId) {
    if (confirm("Êtes-vous sûr de vouloir supprimer ce type d'aide ?")) {
      try {
        const formData = new FormData();
        formData.append("type_aide_id", typeAideId);
        const response = await fetch(`${ROOT}admin/Admin/deleteTypeAide`, {
          method: "POST",
          body: formData,
        });

        const data = await response.json();

        if (data.status === "success") {
          alert(data.message);
          loadTypeAidesFromBackend(
            typeAidesPagination.currentPage,
            typeAidesPagination.itemsPerPage
          );
        } else {
          console.error("Error deleting type aide:", data.message);
          alert("Erreur: " + data.message);
        }
      } catch (error) {
        console.error("Error deleting type aide:", error);
        alert(
          "Une erreur s'est produite lors de la suppression du type d'aide."
        );
      }
    }
  }
}
