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

  const inscriptionPagination = {
    currentPage: 1,
    itemsPerPage: 10,
    totalItems: 0,
    totalPages: 1,
  };

  initializeEventListeners();
  loadRegistrationsFromBackend(
    inscriptionPagination.currentPage,
    inscriptionPagination.itemsPerPage
  );

  function initializeEventListeners() {
    document.addEventListener("click", function (e) {
      const target = e.target.closest(".approve-button");
      if (target) {
        const memberId = target.getAttribute("data-id");
        approveMember(memberId);
      }
    });
    document.addEventListener("click", function (e) {
      const target = e.target.closest(".refuse-button");
      if (target) {
        const memberId = target.getAttribute("data-id");
        deleteMember(memberId);
      }
    });

    document.getElementById("pagination").addEventListener("click", (e) => {
      const target = e.target.closest("button");
      if (!target) return;

      if (target.hasAttribute("data-action")) {
        const action = target.getAttribute("data-action");
        if (action === "previous" && inscriptionPagination.currentPage > 1) {
          changePage(inscriptionPagination.currentPage - 1);
        } else if (
          action === "next" &&
          inscriptionPagination.currentPage < inscriptionPagination.totalPages
        ) {
          changePage(inscriptionPagination.currentPage + 1);
        } else if (action === "page") {
          const page = parseInt(target.getAttribute("data-page"));
          changePage(page);
        }
      }
    });
  }

  function updateTable(members) {
    const tableBody = document.getElementById("registrationsTableBody");
    tableBody.innerHTML = members
      .map(
        (member) => `
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
            <td class="px-6 py-4">${member.id}</td>
            <td class="px-6 py-4">
                <button onclick="showImageModal('${member.photo}')" class="text-blue-600 hover:text-blue-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
            </td>
            <td class="px-6 py-4">${member.nom}</td>
            <td class="px-6 py-4">${member.prenom}</td>
            <td class="px-6 py-4">
                <button onclick="showImageModal('${member.piece_identite}')" class="text-blue-600 hover:text-blue-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
            </td>
            <td class="px-6 py-4">${member.email}</td>
            <td class="px-6 py-4">${member.adresse}</td>
            <td class="px-6 py-4">${member.numero_de_telephone}</td>
            <td class="px-6 py-4">
                <button class="text-green-600 hover:text-green-800 approve-button" data-id="${member.id}">Approuver</button>
                <button class="text-red-600 hover:text-red-800 refuse-button" data-id="${member.id}">Refuser</button>
            </td>
        </tr>
    `
      )
      .join("");

    updatePaginationInfo(inscriptionPagination.totalItems);
  }

  function showImageModal(imageUrl) {
    let path = imageUrl;
    let trimmedPath = imageUrl.includes("public/")
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

  async function approveMember(memberId) {
    try {
      const formData = new FormData();
      formData.append("membre_id", memberId);

      const response = await fetch(`${ROOT}admin/Admin/approveMember`, {
        method: "POST",
        body: formData,
      });

      const data = await response.json();

      if (data.status === "success") {
        alert("Inscription approuvée avec succès.");
        loadRegistrationsFromBackend(
          inscriptionPagination.currentPage,
          inscriptionPagination.itemsPerPage
        );
        loadMembersFromBackend(1, memberPagination.itemsPerPage);
      } else {
        console.error("Error loading members:", data.message);
      }
    } catch (error) {
      console.error("Error loading members:", error);
    }
  }

  async function deleteMember(memberId) {
    try {
      const formData = new FormData();
      formData.append("membre_id", memberId);

      const response = await fetch(`${ROOT}admin/Admin/deleteMember`, {
        method: "POST",
        body: formData,
      });

      const data = await response.json();

      if (data.status === "success") {
        alert("Inscription rejetée.");
        loadRegistrationsFromBackend(
          inscriptionPagination.currentPage,
          inscriptionPagination.itemsPerPage
        );
      } else {
        console.error("Error loading members:", data.message);
      }
    } catch (error) {
      console.error("Error loading members:", error);
    }
  }

  function updatePaginationInfo(totalItems) {
    const start = Math.min(
      (inscriptionPagination.currentPage - 1) *
        inscriptionPagination.itemsPerPage +
        1,
      totalItems
    );
    const end = Math.min(
      inscriptionPagination.currentPage * inscriptionPagination.itemsPerPage,
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
              inscriptionPagination.currentPage === 1
                ? "cursor-not-allowed opacity-50"
                : ""
            }" ${inscriptionPagination.currentPage === 1 ? "disabled" : ""}>
                <span class="sr-only">Précédent</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </li>`;

    for (let i = 1; i <= inscriptionPagination.totalPages; i++) {
      pagination.innerHTML += `
            <li>
                <button data-action="page" data-page="${i}" class="flex items-center justify-center px-3 py-2 text-sm leading-tight ${
        inscriptionPagination.currentPage === i
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
              inscriptionPagination.currentPage ===
              inscriptionPagination.totalPages
                ? "cursor-not-allowed opacity-50"
                : ""
            }" ${
      inscriptionPagination.currentPage === inscriptionPagination.totalPages
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
    if (page >= 1 && page <= inscriptionPagination.totalPages) {
      inscriptionPagination.currentPage = page;
      loadRegistrationsFromBackend(page, inscriptionPagination.itemsPerPage);
    }
  }

  async function loadRegistrationsFromBackend(
    page = 1,
    limit = 10,
    nom = null
  ) {
    try {
      const params = new URLSearchParams({
        page: page.toString(),
        limit: limit.toString(),
      });

      const url = `${ROOT}admin/Admin/getAllRegistrations?${params.toString()}`;
      const response = await fetch(url);
      const data = await response.json();

      if (data.status === "success") {
        updateTable(data.data);
        inscriptionPagination.totalItems = data.pagination.total;
        inscriptionPagination.totalPages = data.pagination.total_pages;
        updatePagination();
        updatePaginationInfo(data.pagination.total);
      } else {
        console.error("Error loading members:", data.message);
      }
    } catch (error) {
      console.error("Error loading members:", error);
    }
  }

  // members section

  const memberPagination = {
    currentPage: 1,
    itemsPerPage: 10,
    totalItems: 0,
    totalPages: 1,
  };

  initializeMembersEventListeners();
  loadMembersFromBackend(
    memberPagination.currentPage,
    memberPagination.itemsPerPage
  );

  function initializeMembersEventListeners() {
    document
      .getElementById("searchMembersByName")
      .addEventListener("input", (e) => {
        loadMembersFromBackend(
          memberPagination.currentPage,
          memberPagination.itemsPerPage,
          e.target.value
        );
      });

    document.addEventListener("click", function (e) {
      const target = e.target.closest(".delete-button");
      if (target) {
        const memberId = target.getAttribute("data-id");
        deleteMember(memberId);
      }
    });

    document
      .getElementById("filterMembersByDate")
      .addEventListener("change", (e) => {
        loadMembersFromBackend(
          memberPagination.currentPage,
          memberPagination.itemsPerPage,
          null,
          e.target.value
        );
      });

    document
      .getElementById("membersPagination")
      .addEventListener("click", (e) => {
        const target = e.target.closest("button");
        if (!target) return;

        if (target.hasAttribute("data-action")) {
          const action = target.getAttribute("data-action");
          if (action === "previous" && memberPagination.currentPage > 1) {
            changeMembersPage(memberPagination.currentPage - 1);
          } else if (
            action === "next" &&
            memberPagination.currentPage < memberPagination.totalPages
          ) {
            changeMembersPage(memberPagination.currentPage + 1);
          } else if (action === "page") {
            const page = parseInt(target.getAttribute("data-page"));
            changeMembersPage(page);
          }
        }
      });
  }

  function updateMembersTable(members) {
    const tableBody = document.getElementById("membersTableBody");
    tableBody.innerHTML = members
      .map(
        (member) => `
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td class="px-6 py-4">${member.id}</td>
                <td class="px-6 py-4">
                    <button onclick="showImageModal('${
                      member.photo
                    }')" class="text-blue-600 hover:text-blue-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </td>
                <td class="px-6 py-4">${member.nom}</td>
                <td class="px-6 py-4">${member.prenom}</td>
                <td class="px-6 py-4">
                    <button onclick="showImageModal('${
                      member.piece_identite
                    }')" class="text-blue-600 hover:text-blue-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </td>
                <td class="px-6 py-4">${member.email}</td>
                <td class="px-6 py-4">${member.adresse}</td>
                <td class="px-6 py-4">${member.numero_de_telephone}</td>
                <td class="px-6 py-4">${
                  member.abonnement_type_abonnement ?? "/"
                }</td>
                <td class="px-6 py-4">${new Date(
                  member.created_at
                ).toLocaleDateString("fr-FR", {
                  day: "2-digit",
                  month: "2-digit",
                  year: "2-digit",
                })}</td>
                <td class="px-6 py-4">
                    <button class="text-red-600 hover:text-red-800 delete-button" data-id="${
                      member.id
                    }">Supprimer</button>
                </td>
            </tr>
        `
      )
      .join("");

    updateMembersPaginationInfo(memberPagination.totalItems);
  }

  function updateMembersPaginationInfo(totalItems) {
    const start = Math.min(
      (memberPagination.currentPage - 1) * memberPagination.itemsPerPage + 1,
      totalItems
    );
    const end = Math.min(
      memberPagination.currentPage * memberPagination.itemsPerPage,
      totalItems
    );

    document.getElementById("membersStartIndex").textContent = start;
    document.getElementById("membersEndIndex").textContent = end;
    document.getElementById("membersTotalItems").textContent = totalItems;
  }

  function updateMembersPagination() {
    const pagination = document.getElementById("membersPagination");
    pagination.innerHTML = `
        <li>
            <button data-action="previous" class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 ${
              memberPagination.currentPage === 1
                ? "cursor-not-allowed opacity-50"
                : ""
            }" ${memberPagination.currentPage === 1 ? "disabled" : ""}>
                <span class="sr-only">Précédent</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </li>`;

    for (let i = 1; i <= memberPagination.totalPages; i++) {
      pagination.innerHTML += `
            <li>
                <button data-action="page" data-page="${i}" class="flex items-center justify-center px-3 py-2 text-sm leading-tight ${
        memberPagination.currentPage === i
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
              memberPagination.currentPage === memberPagination.totalPages
                ? "cursor-not-allowed opacity-50"
                : ""
            }" ${
      memberPagination.currentPage === memberPagination.totalPages
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

  function changeMembersPage(page) {
    if (page >= 1 && page <= memberPagination.totalPages) {
      memberPagination.currentPage = page;
      loadMembersFromBackend(page, memberPagination.itemsPerPage);
    }
  }

  async function loadMembersFromBackend(
    page = 1,
    limit = 10,
    nom = null,
    date = null
  ) {
    try {
      const params = new URLSearchParams({
        page: page.toString(),
        limit: limit.toString(),
      });

      if (nom) params.append("nom", nom);
      if (date) params.append("date_inscription", date);

      const url = `${ROOT}admin/Admin/getAllMembers?${params.toString()}`;
      const response = await fetch(url);
      const data = await response.json();

      if (data.status === "success") {
        updateMembersTable(data.data);
        memberPagination.totalItems = data.pagination.total;
        memberPagination.totalPages = data.pagination.total_pages;
        updateMembersPagination();
        updateMembersPaginationInfo(data.pagination.total);
      } else {
        console.error("Error loading members:", data.message);
      }
    } catch (error) {
      console.error("Error loading members:", error);
    }
  }
}
