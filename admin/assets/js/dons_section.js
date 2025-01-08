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

  // Gestion des événements
  initializeEventListeners();
  loadEventsFromBackend(
    eventPagination.currentPage,
    eventPagination.itemsPerPage
  );

  function initializeEventListeners() {
    document.getElementById("btnCreerEvent").addEventListener("click", () => {
      const modal = document.getElementById("createEventModal");
      const form = modal.querySelector("form");
      form.reset();
      modal.classList.remove("hidden");
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
}
