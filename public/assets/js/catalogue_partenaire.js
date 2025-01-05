document.addEventListener("DOMContentLoaded", function () {
  const ROOT = "http://localhost/TDWProject/public/";
  const partnersContainer = document.getElementById("partnersContainer");
  const citySearch = document.getElementById("citySearch");
  const categoryTabs = document.getElementById("categoryTabs");
  const pagination = document.getElementById("pagination");
  const startIndex = document.getElementById("startIndex");
  const endIndex = document.getElementById("endIndex");
  const totalItems = document.getElementById("totalItems");

  let currentPage = 1;
  const partnersPerPage = 6;
  let currentCategory = null;
  let filteredData = [];

  function fetchCategories() {
    fetch(`${ROOT}Admin/getAllCategories`)
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          const categories = data.data;
          categories.forEach((category) => {
            const tab = document.createElement("button");
            tab.textContent = category.nom;
            tab.className =
              "px-4 py-2 bg-primary text-white rounded hover:bg-secondary-dark";
            tab.addEventListener("click", () => {
              currentCategory = category.id;
              filterPartners();
            });
            categoryTabs.appendChild(tab);
          });
          if (categories.length > 0) {
            currentCategory = categories[0].id;
            filterPartners();
          }
        }
      })
      .catch((error) => console.error("Error fetching categories:", error));
  }

  function fetchPartnersByCategory(categoryId, searchTerm = "", page = 1) {
    const limit = partnersPerPage;

    let url = `${ROOT}Admin/getAllPartners?categorie_id=${categoryId}&page=${page}&limit=${limit}`;
    if (searchTerm) {
      url += `&ville=${searchTerm}`;
    }

    fetch(url)
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          filteredData = data.data;
          displayPartners(filteredData, page);
          renderPagination(data.pagination.total);
        }
      })
      .catch((error) => console.error("Error fetching partners:", error));
  }

  function escapeString(str) {
    return str.replace(/'/g, "\\'").replace(/"/g, '\\"');
  }

  function displayPartners(data, page) {
    partnersContainer.innerHTML = "";
    const start = (page - 1) * partnersPerPage;
    const end = start + partnersPerPage;
    const paginatedData = data.slice(start, end);

    paginatedData.forEach((partner) => {
      let path = partner.logo;
      let trimmedPath = path.split("public/")[1];
      console.log(trimmedPath);
      const card = document.createElement("div");
      card.className =
        "bg-white rounded-lg shadow-md p-6 hover:shadow-lg hover:bg-gray-100 transition-shadow transform hover:scale-105";
      card.innerHTML = `
                <img src="${ROOT}${trimmedPath}" alt="${
        partner.nom
      }" class="w-24 h-24 mx-auto mb-4 rounded-full">
                <h3 class="text-xl font-bold text-primary text-center">${
                  partner.nom
                }</h3>
                <p class="text-text-secondary text-center mt-2"><strong>Ville:</strong> ${
                  partner.ville
                }</p>
                <p class="text-text-secondary text-center"><strong>Remise:</strong> ${
                  partner.remise
                }</p>
                <button onclick="showPartnerDetails('${escapeString(
                  partner.nom
                )}', '${partner.ville}', '${partner.email}', '${
        partner.adresse
      }', '${partner.numero_de_telephone}', '${
        partner.site_web
      }', '${ROOT}${trimmedPath}')" class="mt-4 bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark w-full">
                    Plus de détails
                </button>
            `;
      partnersContainer.appendChild(card);
    });

    startIndex.textContent = start + 1;
    endIndex.textContent = Math.min(end, data.length);
    totalItems.textContent = filteredData.length;
  }

  window.showPartnerDetails = function (
    name,
    city,
    email,
    address,
    phone,
    website,
    logo
  ) {
    const modal = document.createElement("div");
    modal.className =
      "fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4";
    modal.innerHTML = `
            <div class="bg-white p-8 rounded-lg shadow-md max-w-2xl w-full">
                <h2 class="text-2xl font-bold text-primary mb-4">${name}</h2>
                <img src="${logo}" alt="${name}" class="w-32 h-32 mb-4 mx-auto rounded-full">
                <p class="text-text-secondary"><strong>Ville:</strong> ${city}</p>
                <p class="text-text-secondary"><strong>Email:</strong> ${email}</p>
                <p class="text-text-secondary"><strong>Adresse:</strong> ${address}</p>
                <p class="text-text-secondary"><strong>Téléphone:</strong> ${phone}</p>
                <p class="text-text-secondary"><strong>Site Web:</strong> <a href="${website}" class="text-primary hover:underline">${website}</a></p>
                <button onclick="this.parentElement.parentElement.remove()" class="mt-4 bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark">Fermer</button>
            </div>
        `;
    document.body.appendChild(modal);
  };

  function renderPagination(totalItems) {
    pagination.innerHTML = "";
    const totalPages = Math.ceil(totalItems / partnersPerPage);

    for (let i = 1; i <= totalPages; i++) {
      const button = document.createElement("button");
      button.textContent = i;
      button.className = `px-4 py-2 mx-1 rounded ${
        i === currentPage
          ? "bg-primary text-white"
          : "bg-gray-200 text-gray-700 hover:bg-gray-300"
      }`;
      button.addEventListener("click", () => {
        currentPage = i;
        fetchPartnersByCategory(currentCategory, citySearch.value, currentPage);
      });
      pagination.appendChild(button);
    }
  }

  citySearch.addEventListener("input", (e) => {
    filterPartners();
  });

  function filterPartners() {
    const searchTerm = citySearch.value.toLowerCase();
    fetchPartnersByCategory(currentCategory, searchTerm, currentPage);
  }

  fetchCategories();
});
