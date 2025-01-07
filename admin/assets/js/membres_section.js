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

  const memberPagination = {
    currentPage: 1,
    itemsPerPage: 10,
    totalItems: 0,
    totalPages: 1,
  };

  initializeEventListeners();
  loadMembersFromBackend(
    memberPagination.currentPage,
    memberPagination.itemsPerPage
  );

  function initializeEventListeners() {
    document.getElementById("pagination").addEventListener("click", (e) => {
      const target = e.target.closest("button");
      if (!target) return;

      if (target.hasAttribute("data-action")) {
        const action = target.getAttribute("data-action");
        if (action === "previous" && memberPagination.currentPage > 1) {
          changePage(memberPagination.currentPage - 1);
        } else if (
          action === "next" &&
          memberPagination.currentPage < memberPagination.totalPages
        ) {
          changePage(memberPagination.currentPage + 1);
        } else if (action === "page") {
          const page = parseInt(target.getAttribute("data-page"));
          changePage(page);
        }
      }
    });
  }

  function updateTable(members) {
    const tableBody = document.getElementById("membersTableBody");
    tableBody.innerHTML = members
      .map(
        (member) => `
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
            <td class="px-6 py-4">
                <input type="checkbox" class="w-4 h-4" data-id="${member.id}">
            </td>
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
                <button onclick="showImageModal('${member.reçu_de_paiement}')" class="text-blue-600 hover:text-blue-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
            </td>
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
                <button onclick="approveMember(${member.id})" class="text-green-600 hover:text-green-800">Approuver</button>
                <button onclick="rejectMember(${member.id})" class="text-red-600 hover:text-red-800">Refuser</button>
            </td>
        </tr>
    `
      )
      .join("");

    updatePaginationInfo(memberPagination.totalItems);
  }

  function showImageModal(imageUrl) {
    // Implement modal logic to show the image
    console.log("Show image:", imageUrl);
  }

  function approveMember(memberId) {
    // Implement logic to approve member
    console.log("Approve member:", memberId);
  }

  function rejectMember(memberId) {
    // Implement logic to reject member
    console.log("Reject member:", memberId);
  }

  function updatePaginationInfo(totalItems) {
    const start = Math.min(
      (memberPagination.currentPage - 1) * memberPagination.itemsPerPage + 1,
      totalItems
    );
    const end = Math.min(
      memberPagination.currentPage * memberPagination.itemsPerPage,
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

  function changePage(page) {
    if (page >= 1 && page <= memberPagination.totalPages) {
      memberPagination.currentPage = page;
      loadMembersFromBackend(page, memberPagination.itemsPerPage);
    }
  }

  async function loadMembersFromBackend(page = 1, limit = 10, nom = null) {
    try {
      const params = new URLSearchParams({
        page: page.toString(),
        limit: limit.toString(),
      });

      if (nom && nom.trim() !== "") {
        params.append("nom", nom);
      }

      const url = `${ROOT}admin/Admin/getAllMembers?${params.toString()}`;
      const response = await fetch(url);
      const data = await response.json();

      if (data.status === "success") {
        updateTable(data.data);
        memberPagination.totalItems = data.pagination.total;
        memberPagination.totalPages = data.pagination.total_pages;
        updatePagination();
        updatePaginationInfo(data.pagination.total);
      } else {
        console.error("Error loading members:", data.message);
      }
    } catch (error) {
      console.error("Error loading members:", error);
    }
  }
}
