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
      if (target === "#benevolats") {
        loadBenevolats();
      }
    });
  });

  if (tabs.length > 0) {
    tabs[0].classList.add("text-blue-600", "border-blue-600");
    document
      .querySelector(tabs[0].getAttribute("href"))
      .classList.remove("hidden");
  }

  const eventPagination = {
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

  initializeEventListenersForEvents();
  loadEventsFromBackend(
    eventPagination.currentPage,
    eventPagination.itemsPerPage
  );

  function initializeEventListenersForEvents() {
    document.getElementById("btnCreerEvent").addEventListener("click", () => {
      const modal = document.getElementById("createEventModal");
      const form = modal.querySelector("form");
      form.reset();
      modal.classList.remove("hidden");
      modal.classList.remove("area-hidden");
    });

    document
      .querySelector("#createEventModal form")
      .addEventListener("submit", async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);

        try {
          const response = await fetch(`${ROOT}admin/Admin/addEvent`, {
            method: "POST",
            body: formData,
          });

          const data = await response.json();

          if (data.status === "success") {
            alert(data.message);
            loadEventsFromBackend(
              eventPagination.currentPage,
              eventPagination.itemsPerPage
            );
            e.target.reset();
            document.getElementById("createEventModal").classList.add("hidden");
          } else {
            console.error("Error creating event:", data.message);
            alert("Erreur: " + data.message);
          }
        } catch (error) {
          console.error("Error creating event:", error);
          alert(
            "Une erreur s'est produite lors de la création de l'événement."
          );
        }
      });

    const closeEventButtons = document.querySelectorAll(
      '[data-modal-toggle="createEventModal"]'
    );
    closeEventButtons.forEach((button) => {
      button.addEventListener("click", () => {
        const modal = document.getElementById("createEventModal");
        if (modal) {
          modal.classList.add("hidden");
          modal.classList.add("area-hidden");
        }
      });
    });

    document.getElementById("EventTableBody").addEventListener("click", (e) => {
      const target = e.target;
      if (target.getAttribute("data-action") === "delete") {
        const eventId = target.getAttribute("data-id");
        deleteEvent(eventId);
      }
    });

    document
      .getElementById("paginationEvent")
      .addEventListener("click", (e) => {
        const target = e.target.closest("button");
        if (!target) return;

        if (target.hasAttribute("data-action")) {
          const action = target.getAttribute("data-action");
          if (action === "previous" && eventPagination.currentPage > 1) {
            changeEventPage(eventPagination.currentPage - 1);
          } else if (
            action === "next" &&
            eventPagination.currentPage < eventPagination.totalPages
          ) {
            changeEventPage(eventPagination.currentPage + 1);
          } else if (action === "page") {
            const page = parseInt(target.getAttribute("data-page"));
            changeEventPage(page);
          }
        }
      });
  }

  async function loadEventsFromBackend(page = 1, limit = 10) {
    try {
      const params = new URLSearchParams({
        page: page.toString(),
        limit: limit.toString(),
      });

      const url = `${ROOT}admin/Admin/getAllEvents?${params.toString()}`;

      const response = await fetch(url);
      const data = await response.json();

      if (data.status === "success") {
        updateEventTable(data.data);
        eventPagination.totalItems = data.pagination.total;
        eventPagination.totalPages = data.pagination.total_pages;
        updateEventPagination();
        updateEventPaginationInfo(data.pagination.total);
      } else {
        console.error("Error loading events:", data.message);
      }
    } catch (error) {
      console.error("Error loading events:", error);
    }
  }

  function updateEventTable(events) {
    const tableBody = document.getElementById("EventTableBody");
    tableBody.innerHTML = events
      .map(
        (event) => `
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    ${event.id}
                </td>
                <td class="px-6 py-4">${event.titre}</td>
                <td class="px-6 py-4">${event.description}</td>
                <td class="px-6 py-4">${event.lieu}</td>
                <td class="px-6 py-4">${event.date_debut}</td>
                <td class="px-6 py-4">${event.date_fin}</td>
                <td class="px-6 py-4 ">
                    <button data-action="edit" data-id="${event.id}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline mr-3">
                        Modifier
                    </button>
                    <button data-action="delete" data-id="${event.id}" class="font-medium text-red-600 dark:text-red-500 hover:underline">
                        Supprimer
                    </button>
                </td>
            </tr>
        `
      )
      .join("");

    updateEventPaginationInfo(eventPagination.totalItems);
    updateEventPagination();
  }

  function updateEventPagination() {
    const pagination = document.getElementById("paginationEvent");
    pagination.innerHTML = `
            <li>
                <button data-action="previous" class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 ${
                  eventPagination.currentPage === 1
                    ? "cursor-not-allowed opacity-50"
                    : ""
                }" ${eventPagination.currentPage === 1 ? "disabled" : ""}>
                    <span class="sr-only">Précédent</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </li>`;

    for (let i = 1; i <= eventPagination.totalPages; i++) {
      pagination.innerHTML += `
                <li>
                    <button data-action="page" data-page="${i}" class="flex items-center justify-center px-3 py-2 text-sm leading-tight ${
        eventPagination.currentPage === i
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
                  eventPagination.currentPage === eventPagination.totalPages
                    ? "cursor-not-allowed opacity-50"
                    : ""
                }" ${
      eventPagination.currentPage === eventPagination.totalPages
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

  function updateEventPaginationInfo(totalItems) {
    const start = Math.min(
      (eventPagination.currentPage - 1) * eventPagination.itemsPerPage + 1,
      totalItems
    );
    const end = Math.min(
      eventPagination.currentPage * eventPagination.itemsPerPage,
      totalItems
    );

    document.getElementById("startIndexEvent").textContent = start;
    document.getElementById("endIndexEvent").textContent = end;
    document.getElementById("totalItemsEvent").textContent = totalItems;
  }

  function changeEventPage(page) {
    if (page >= 1 && page <= eventPagination.totalPages) {
      eventPagination.currentPage = page;
      loadEventsFromBackend(page, eventPagination.itemsPerPage);
    }
  }

  async function deleteEvent(eventId) {
    if (confirm("Êtes-vous sûr de vouloir supprimer cet événement ?")) {
      try {
        const formData = new FormData();
        formData.append("evenement_id", eventId);
        const response = await fetch(`${ROOT}admin/Admin/deleteEvent`, {
          method: "POST",
          body: formData,
        });

        const data = await response.json();

        if (data.status === "success") {
          alert(data.message);
          loadEventsFromBackend(
            eventPagination.currentPage,
            eventPagination.itemsPerPage
          );
        } else {
          console.error("Error deleting event:", data.message);
          alert("Erreur: " + data.message);
        }
      } catch (error) {
        console.error("Error deleting event:", error);
        alert(
          "Une erreur s'est produite lors de la suppression de l'événement."
        );
      }
    }
  }

  // gestion dons
  const donsPagination = {
    currentPage: 1,
    itemsPerPage: 10,
    totalItems: 0,
    totalPages: 1,
  };

  function initializeEventListeners() {
    document.addEventListener("click", function (e) {
      const target = e.target.closest(".approve-button");
      if (target) {
        const donId = target.getAttribute("data-id");
        approveDon(donId);
      }
    });

    document.addEventListener("click", function (e) {
      const target = e.target.closest(".refuse-button");
      if (target) {
        const donId = target.getAttribute("data-id");
        refuseDon(donId);
      }
    });

    document.getElementById("pagination").addEventListener("click", (e) => {
      const target = e.target.closest("button");
      if (!target) return;

      if (target.hasAttribute("data-action")) {
        const action = target.getAttribute("data-action");
        if (action === "previous" && donsPagination.currentPage > 1) {
          changePage(donsPagination.currentPage - 1);
        } else if (
          action === "next" &&
          donsPagination.currentPage < donsPagination.totalPages
        ) {
          changePage(donsPagination.currentPage + 1);
        } else if (action === "page") {
          const page = parseInt(target.getAttribute("data-page"));
          changePage(page);
        }
      }
    });
  }

  function updateTable(dons) {
    const tableBody = document.getElementById("donsTableBody");
    tableBody.innerHTML = dons
      .map(
        (don) => `
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td class="px-6 py-4">${don.id}</td>
                <td class="px-6 py-4">${don.compte_membre_nom}</td>
                <td class="px-6 py-4">${don.compte_membre_prenom}</td>
                <td class="px-6 py-4">${don.montant} DZD</td>
                <td class="px-6 py-4">
                    <button onclick="showReceipt('${
                      don.recu_paiement
                    }')" class="text-blue-600 hover:text-blue-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </td>
                <td class="px-6 py-4">${new Date(
                  don.date
                ).toLocaleDateString()}</td>
                <td class="px-6 py-4">${don.est_tracable ? "Oui" : "Non"}</td>
                <td class="px-6 py-4">
                    <button class="text-green-600 hover:text-green-800 approve-button" data-id="${
                      don.id
                    }">Approuver</button>
                    <button class="text-red-600 hover:text-red-800 refuse-button" data-id="${
                      don.id
                    }">Refuser</button>
                </td>
            </tr>
        `
      )
      .join("");

    updatePaginationInfo(donsPagination.totalItems);
  }

  function showReceipt(receiptUrl) {
    let path = receiptUrl;
    let trimmedPath = receiptUrl.includes("public/")
      ? path.split("public/")[1]
      : path;
    const modal = document.createElement("div");
    modal.classList.add(
      "fixed",
      "inset-0",
      "bg-black",
      "bg-opacity-50",
      "flex",
      "items-center",
      "justify-center",
      "z-50"
    );

    modal.innerHTML = `
      <div class="bg-white p-4 rounded-lg shadow-lg max-w-md w-full relative">
        <button class="absolute top-2 right-2 text-primary hover:text-secondarys focus:outline-none" id="closeModal">
          ✖
        </button>
        <img src="${ROOT}public/${trimmedPath}" alt="Image" class="w-full h-auto max-w-[500px] max-h-[500px] rounded">
      </div>
    `;

    document.body.appendChild(modal);

    const closeModal = document.getElementById("closeModal");
    closeModal.addEventListener("click", () => {
      modal.remove();
    });

    modal.addEventListener("click", (event) => {
      if (event.target === modal) {
        modal.remove();
      }
    });
  }

  async function approveDon(donId) {
    try {
      const formData = new FormData();
      formData.append("don_id", donId);

      const response = await fetch(`${ROOT}admin/Admin/validerDon`, {
        method: "POST",
        body: formData,
      });

      const data = await response.json();

      if (data.status === "success") {
        alert("Don approuvé avec succès.");
        loadDonsFromBackend(
          donsPagination.currentPage,
          donsPagination.itemsPerPage
        );
      } else {
        console.error("Erreur lors de l'approbation du don:", data.message);
      }
    } catch (error) {
      console.error("Erreur lors de l'approbation du don:", error);
    }
  }

  async function refuseDon(donId) {
    try {
      const formData = new FormData();
      formData.append("don_id", donId);

      const response = await fetch(`${ROOT}admin/Admin/refuserDon`, {
        method: "POST",
        body: formData,
      });

      const data = await response.json();

      if (data.status === "success") {
        alert("Don refusé avec succès.");
        loadDonsFromBackend(
          donsPagination.currentPage,
          donsPagination.itemsPerPage
        );
      } else {
        console.error("Erreur lors du refus du don:", data.message);
      }
    } catch (error) {
      console.error("Erreur lors du refus du don:", error);
    }
  }

  function updatePaginationInfo(totalItems) {
    const start = Math.min(
      (donsPagination.currentPage - 1) * donsPagination.itemsPerPage + 1,
      totalItems
    );
    const end = Math.min(
      donsPagination.currentPage * donsPagination.itemsPerPage,
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
              donsPagination.currentPage === 1
                ? "cursor-not-allowed opacity-50"
                : ""
            }" ${donsPagination.currentPage === 1 ? "disabled" : ""}>
                <span class="sr-only">Précédent</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </li>`;

    for (let i = 1; i <= donsPagination.totalPages; i++) {
      pagination.innerHTML += `
            <li>
                <button data-action="page" data-page="${i}" class="flex items-center justify-center px-3 py-2 text-sm leading-tight ${
        donsPagination.currentPage === i
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
              donsPagination.currentPage === donsPagination.totalPages
                ? "cursor-not-allowed opacity-50"
                : ""
            }" ${
      donsPagination.currentPage === donsPagination.totalPages ? "disabled" : ""
    }>
                <span class="sr-only">Suivant</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </li>`;
  }

  function changePage(page) {
    if (page >= 1 && page <= donsPagination.totalPages) {
      donsPagination.currentPage = page;
      loadDonsFromBackend(page, donsPagination.itemsPerPage);
    }
  }

  async function loadDonsFromBackend(page = 1, limit = 10) {
    try {
      const params = new URLSearchParams({
        page: page.toString(),
        limit: limit.toString(),
      });

      const url = `${ROOT}admin/Admin/getAllDons?${params.toString()}`;
      const response = await fetch(url);
      const data = await response.json();

      if (data.status === "success") {
        updateTable(data.data);
        donsPagination.totalItems = data.pagination.total;
        donsPagination.totalPages = data.pagination.total_pages;
        updatePagination();
        updatePaginationInfo(data.pagination.total);
      } else {
        console.error("Erreur lors du chargement des dons:", data.message);
      }
    } catch (error) {
      console.error("Erreur lors du chargement des dons:", error);
    }
  }

  async function loadBenevolats() {
    try {
      const response = await fetch(`${ROOT}admin/Admin/getAllBenevolats`);
      const data = await response.json();
      if (data.status === "success") {
        updateBenevolatsTable(data.data);
      }
    } catch (error) {
      console.error("Error loading benevolats:", error);
    }
  }

  function updateBenevolatsTable(benevolats) {
    const tableBody = document.getElementById("benevolatsTableBody");
    tableBody.innerHTML = benevolats
      .map(
        (b) => `
        <tr class="border-b hover:bg-gray-50">
          <td class="px-6 py-4">${b.id}</td>
          <td class="px-6 py-4">${b.membre_nom} ${b.membre_prenom}</td>
          <td class="px-6 py-4">${b.evenement_titre}</td>
          <td class="px-6 py-4">${b.statut}</td>
          <td class="px-6 py-4">
            <button data-action="approveBenevolat" data-id="${b.id}" class="text-green-600 hover:underline">
              Approuver
            </button>
            <button data-action="refuseBenevolat" data-id="${b.id}" class="text-red-600 hover:underline">
              Refuser
            </button>
          </td>
        </tr>
      `
      )
      .join("");
  }

  document
    .getElementById("benevolatsTableBody")
    .addEventListener("click", (e) => {
      const action = e.target.getAttribute("data-action");
      const benevolatId = e.target.getAttribute("data-id");
      if (!action || !benevolatId) return;

      if (action === "approveBenevolat") {
        approveBenevolat(benevolatId);
      } else if (action === "refuseBenevolat") {
        refuseBenevolat(benevolatId);
      }
    });

  async function approveBenevolat(id) {
    try {
      const response = await fetch(ROOT + "admin/Admin/approveBenevolat", {
        method: "POST",
        body: new URLSearchParams({ benevolat_id: id }),
      });
      const data = await response.json();
      if (data.status === "success") {
        alert("Bénévolat approuvé avec succès.");
        loadBenevolats();
      } else {
        alert("Erreur lors de l'approbation du bénévolat.");
      }
    } catch (error) {
      console.error("Error approving benevolat:", error);
    }
  }

  async function refuseBenevolat(id) {
    try {
      const response = await fetch(ROOT + "admin/Admin/refuseBenevolat", {
        method: "POST",
        body: new URLSearchParams({ benevolat_id: id }),
      });
      const data = await response.json();
      if (data.status === "success") {
        alert("Bénévolat refusé avec succès.");
        loadBenevolats();
      } else {
        alert("Erreur lors du refus du bénévolat.");
      }
    } catch (error) {
      console.error("Error refusing benevolat:", error);
    }
  }

  initializeEventListeners();
  loadDonsFromBackend(donsPagination.currentPage, donsPagination.itemsPerPage);
}
