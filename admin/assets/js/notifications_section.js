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

  // news section

  const newsPagination = {
    currentPage: 1,
    itemsPerPage: 10,
    totalItems: 0,
    totalPages: 1,
  };

  initializeNewsEventListeners();
  loadNewsFromBackend(newsPagination.currentPage, newsPagination.itemsPerPage);

  function initializeNewsEventListeners() {
    document.getElementById("btnCreerNews").addEventListener("click", () => {
      const modal = document.getElementById("createNewsModal");
      const form = modal.querySelector("form");
      form.reset();
      modal.classList.remove("hidden");
      modal.removeAttribute("aria-hidden");
    });

    document
      .querySelector("#createNewsModal form")
      .addEventListener("submit", async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);

        const titre = formData.get("titre");
        const contenu = formData.get("contenu");
        const image = formData.get("image");

        if (!titre || !contenu) {
          alert("Veuillez remplir tous les champs requis");
          return;
        }

        try {
          const url = `${ROOT}admin/Admin/addNewsArticle`;
          const response = await fetch(url, {
            method: "POST",
            body: formData,
          });

          const data = await response.json();

          if (data.status === "success") {
            alert(data.message);
            loadNewsFromBackend(
              newsPagination.currentPage,
              newsPagination.itemsPerPage
            );
            e.target.reset();
            document.getElementById("createNewsModal").classList.add("hidden");
          } else {
            console.error(
              "Erreur lors de la création de l'article:",
              data.message
            );
            alert("Erreur: " + data.message);
          }
        } catch (error) {
          console.error("Erreur lors de la création de l'article:", error);
          alert("Une erreur s'est produite lors de la création de l'article.");
        }
      });

    const closeNewsButtons = document.querySelectorAll(
      '[data-modal-toggle="createNewsModal"]'
    );
    closeNewsButtons.forEach((button) => {
      button.addEventListener("click", () => {
        const modal = document.getElementById("createNewsModal");
        if (modal) {
          modal.classList.add("hidden");
        }
      });
    });

    document.getElementById("NewsTableBody").addEventListener("click", (e) => {
      const target = e.target;
      if (target.getAttribute("data-action") === "delete") {
        const newsId = target.getAttribute("data-id");
        deleteNews(newsId);
      }
    });

    document.getElementById("paginationNews").addEventListener("click", (e) => {
      const target = e.target.closest("button");
      if (!target) return;

      if (target.hasAttribute("data-action")) {
        const action = target.getAttribute("data-action");
        if (action === "previous" && newsPagination.currentPage > 1) {
          changeNewsPage(newsPagination.currentPage - 1);
        } else if (
          action === "next" &&
          newsPagination.currentPage < newsPagination.totalPages
        ) {
          changeNewsPage(newsPagination.currentPage + 1);
        } else if (action === "page") {
          const page = parseInt(target.getAttribute("data-page"));
          changeNewsPage(page);
        }
      }
    });
  }

  async function loadNewsFromBackend(page = 1, limit = 10) {
    try {
      const params = new URLSearchParams({
        page: page.toString(),
        limit: limit.toString(),
      });

      const url = `${ROOT}admin/Admin/getAllNewsArticles?${params.toString()}`;
      const response = await fetch(url);
      const data = await response.json();

      if (data.status === "success") {
        updateNewsTable(data.data);
        newsPagination.totalItems = data.pagination.total;
        newsPagination.totalPages = data.pagination.total_pages;
        updateNewsPagination();
        updateNewsPaginationInfo(data.pagination.total);
      } else {
        console.error("Erreur lors du chargement des articles:", data.message);
      }
    } catch (error) {
      console.error("Erreur lors du chargement des articles:", error);
    }
  }

  function escapeContent(content) {
    if (!content) return "";

    return (
      content
        // First encode the content for use in HTML attributes
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#39;")
        // Then escape newlines and other special characters for JS string
        .replace(/\r?\n/g, "\\n")
        .replace(/\\/g, "\\\\")
        .replace(/\t/g, "\\t")
    );
  }

  function updateNewsTable(news) {
    const tableBody = document.getElementById("NewsTableBody");
    tableBody.innerHTML = news
      .map(
        (article) => `
              <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                  <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                      ${article.id}
                  </td>
                  <td class="px-6 py-4">${article.titre}</td>
                  <td class="px-6 py-4">
                      <button onclick="showContentModal('${escapeContent(
                        article.contenu
                      )}')" class="text-blue-600 hover:text-blue-800">
                          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                          </svg>
                      </button>
                  </td>
                  <td class="px-6 py-4">
                  ${
                    article.thumbnail_url === null
                      ? "Pas d'image"
                      : `<button
onclick="showImageModal('${article.thumbnail_url}')"
class="text-blue-600 hover:text-blue-800"
>
<svg
class="w-6 h-6"
fill="none"
stroke="currentColor"
viewBox="0 0 24 24"
xmlns="http://www.w3.org/2000/svg"
>
<path
stroke-linecap="round"
stroke-linejoin="round"
stroke-width="2"
d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
></path>
<path
stroke-linecap="round"
stroke-linejoin="round"
stroke-width="2"
d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
></path>
</svg>
</button>
                    `
                  }
                  </td>
                  <td class="px-6 py-4">${article.date_publication}</td>
                  <td class="px-6 py-4">
                      <button data-action="delete" data-id="${
                        article.id
                      }" class="font-medium text-red-600 dark:text-red-500 hover:underline">
                          Supprimer
                      </button>
                  </td>
              </tr>
          `
      )
      .join("");

    updateNewsPaginationInfo(newsPagination.totalItems);
  }

  function updateNewsPagination() {
    const pagination = document.getElementById("paginationNews");
    pagination.innerHTML = `
              <li>
                  <button data-action="previous" class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 ${
                    newsPagination.currentPage === 1
                      ? "cursor-not-allowed opacity-50"
                      : ""
                  }" ${newsPagination.currentPage === 1 ? "disabled" : ""}>
                      <span class="sr-only">Précédent</span>
                      <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                      </svg>
                  </button>
              </li>`;

    for (let i = 1; i <= newsPagination.totalPages; i++) {
      pagination.innerHTML += `
                  <li>
                      <button data-action="page" data-page="${i}" class="flex items-center justify-center px-3 py-2 text-sm leading-tight ${
        newsPagination.currentPage === i
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
                    newsPagination.currentPage === newsPagination.totalPages
                      ? "cursor-not-allowed opacity-50"
                      : ""
                  }" ${
      newsPagination.currentPage === newsPagination.totalPages ? "disabled" : ""
    }>
                      <span class="sr-only">Suivant</span>
                      <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                      </svg>
                  </button>
              </li>`;
  }

  function updateNewsPaginationInfo(totalItems) {
    const start = Math.min(
      (newsPagination.currentPage - 1) * newsPagination.itemsPerPage + 1,
      totalItems
    );
    const end = Math.min(
      newsPagination.currentPage * newsPagination.itemsPerPage,
      totalItems
    );

    document.getElementById("startIndexNews").textContent = start;
    document.getElementById("endIndexNews").textContent = end;
    document.getElementById("totalItemsNews").textContent = totalItems;
  }

  function changeNewsPage(page) {
    if (page >= 1 && page <= newsPagination.totalPages) {
      newsPagination.currentPage = page;
      loadNewsFromBackend(page, newsPagination.itemsPerPage);
    }
  }

  async function deleteNews(newsId) {
    if (confirm("Êtes-vous sûr de vouloir supprimer cet article ?")) {
      try {
        const formData = new FormData();
        formData.append("article_id", newsId);
        const response = await fetch(`${ROOT}admin/Admin/deleteNewsArticle`, {
          method: "POST",
          body: formData,
        });

        const data = await response.json();

        if (data.status === "success") {
          alert(data.message);
          loadNewsFromBackend(
            newsPagination.currentPage,
            newsPagination.itemsPerPage
          );
        } else {
          console.error(
            "Erreur lors de la suppression de l'article:",
            data.message
          );
          alert("Erreur: " + data.message);
        }
      } catch (error) {
        console.error("Erreur lors de la suppression de l'article:", error);
        alert("Une erreur s'est produite lors de la suppression de l'article.");
      }
    }
  }

  function showContentModal(content) {
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
    <div class="bg-white p-4 rounded-lg shadow-lg max-w-2xl w-full relative mx-4">
      <button class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 focus:outline-none" id="closeModal">
        ✖
      </button>
      <div class="overflow-y-auto max-h-[70vh] mt-4">
        <div class="text-gray-700 whitespace-pre-wrap" id="modalContent"></div>
      </div>
    </div>
  `;

    document.body.appendChild(modal);

    // Use textContent to safely insert the content
    const modalContent = modal.querySelector("#modalContent");
    modalContent.textContent = content;

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
}
