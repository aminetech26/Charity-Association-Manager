ROOT = "http://localhost/TDWProject/";

let currentPage = 1;
let currentFilters = {};
let allOffers = []; // Add this at the top with other global variables

function createOfferCard(offer, isSpecial = false) {
  const cardHeader = isSpecial
    ? `
    <div class="relative">
      <img src="${ROOT}${offer.thumbnail_path.replace(
        "../public/",
        "public/"
      )}" 
           alt="${offer.type_offre}"
           class="w-full h-48 object-cover"
           onerror="this.src='${ROOT}public/assets/images/default-offer.jpeg'">
      <div class="absolute top-2 right-2 bg-primary text-white px-3 py-1 rounded-full text-sm">
        Offre Spéciale
      </div>
    </div>`
    : "";

  return `
    <div class="bg-white rounded-lg shadow-lg overflow-hidden ${
      isSpecial ? "border-2 border-primary" : ""
    }">
      ${cardHeader}
      <div class="p-6">
        <div class="flex justify-between items-start mb-4">
          <h3 class="text-xl font-bold text-gray-900">${
            offer.partenaire_nom
          }</h3>
          <span class="text-lg font-semibold text-primary">${
            offer.valeur
          }</span>
        </div>
        <p class="text-gray-600 mb-4">${offer.description}</p>
        <div class="flex justify-between items-center text-sm text-gray-500">
          <span>Type: ${offer.type_offre}</span>
          <span>Valide jusqu'au ${new Date(
            offer.date_fin
          ).toLocaleDateString()}</span>
        </div>
      </div>
    </div>
  `;
}

async function loadOffers(page = 1, filters = {}) {
  try {
    const response = await fetch(`${ROOT}public/Home/fetchOffers`);
    const result = await response.json();

    if (result.status === "success") {
      allOffers = result.data.offers; // Store all offers
      displayOffers(allOffers);
      updatePagination(result.data.pages, page);
    }
  } catch (error) {
    console.error("Error loading offers:", error);
  }
}

function displayOffers(offers) {
  const regularOffersContainer = document.getElementById("regularOffers");
  const specialOffersContainer = document.getElementById("specialOffers");

  if (!regularOffersContainer || !specialOffersContainer) return;

  regularOffersContainer.innerHTML = "";
  specialOffersContainer.innerHTML = "";

  offers.forEach((offer) => {
    const container = offer.is_special
      ? specialOffersContainer
      : regularOffersContainer;
    container.innerHTML += createOfferCard(offer, offer.is_special);
  });
}

function updatePagination(totalPages, currentPage) {
  const pagination = document.getElementById("pagination");
  if (!pagination) return;

  pagination.innerHTML = `
        <button class="px-3 py-1 rounded-md ${
          currentPage === 1
            ? "bg-gray-100 text-gray-400"
            : "bg-white text-gray-700"
        }" 
                ${currentPage === 1 ? "disabled" : ""}
                onclick="changePage(${currentPage - 1})">
            Précédent
        </button>
        ${Array.from({ length: totalPages }, (_, i) => i + 1)
          .map(
            (page) => `
                <button class="px-3 py-1 rounded-md ${
                  page === currentPage
                    ? "bg-primary text-white"
                    : "bg-white text-gray-700"
                }"
                        onclick="changePage(${page})">
                    ${page}
                </button>
            `
          )
          .join("")}
        <button class="px-3 py-1 rounded-md ${
          currentPage === totalPages
            ? "bg-gray-100 text-gray-400"
            : "bg-white text-gray-700"
        }"
                ${currentPage === totalPages ? "disabled" : ""}
                onclick="changePage(${currentPage + 1})">
            Suivant
        </button>
    `;
}

function changePage(page) {
  currentPage = page;
  loadOffers(page, currentFilters);
}

document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.getElementById("offerSearch");
  const sortSelect = document.getElementById("sortValue");

  // Simple client-side search
  if (searchInput) {
    searchInput.addEventListener("input", (e) => {
      const searchTerm = e.target.value.toLowerCase();
      const filteredOffers = allOffers.filter(
        (offer) =>
          offer.partenaire_nom.toLowerCase().includes(searchTerm) ||
          offer.description.toLowerCase().includes(searchTerm) ||
          offer.type_offre.toLowerCase().includes(searchTerm)
      );
      displayOffers(filteredOffers);
    });
  }

  if (sortSelect) {
    sortSelect.addEventListener("change", () => {
      const sortedOffers = [...allOffers].sort((a, b) => {
        switch (sortSelect.value) {
          case "date_asc":
            return new Date(a.date_fin) - new Date(b.date_fin);
          case "date_desc":
            return new Date(b.date_fin) - new Date(a.date_fin);
          case "value_asc":
            return parseFloat(a.valeur) - parseFloat(b.valeur);
          case "value_desc":
            return parseFloat(b.valeur) - parseFloat(a.valeur);
          default:
            return 0;
        }
      });
      displayOffers(sortedOffers);
    });
  }

  loadOffers(1);
});
