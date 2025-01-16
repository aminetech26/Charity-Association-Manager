const ROOT = "http://localhost/TDWProject/";

class Carousel {
  constructor(element, options = {}) {
    this.carousel = element;
    this.options = {
      autoplayInterval: options.autoplayInterval || 3000,
      pauseOnHover: options.pauseOnHover ?? true,
    };

    this.items = this.carousel.querySelectorAll("[data-carousel-item]");
    this.indicators = this.carousel.querySelectorAll(
      "[data-carousel-slide-to]"
    );
    this.prevButton = this.carousel.querySelector("[data-carousel-prev]");
    this.nextButton = this.carousel.querySelector("[data-carousel-next]");
    this.currentIndex = 0;
    this.autoplayInterval = null;

    this.init();
  }

  init() {
    if (!this.carousel) return;

    this.setupEventListeners();
    this.showItem(this.currentIndex);
    this.startAutoplay();
  }

  setupEventListeners() {
    if (this.prevButton) {
      this.prevButton.addEventListener("click", () => this.prevItem());
    }

    if (this.nextButton) {
      this.nextButton.addEventListener("click", () => this.nextItem());
    }

    this.indicators.forEach((indicator, index) => {
      indicator.addEventListener("click", () => this.showItem(index));
    });

    if (this.options.pauseOnHover) {
      this.carousel.addEventListener("mouseenter", () => this.pauseAutoplay());
      this.carousel.addEventListener("mouseleave", () => this.startAutoplay());
    }

    document.addEventListener("visibilitychange", () => {
      document.hidden ? this.pauseAutoplay() : this.startAutoplay();
    });
  }

  showItem(index) {
    this.items.forEach((item, i) => {
      item.classList.toggle("hidden", i !== index);
      item.classList.toggle("opacity-0", i !== index);
      item.classList.toggle("opacity-100", i === index);
    });

    this.indicators.forEach((indicator, i) => {
      indicator.setAttribute("aria-current", i === index);
      indicator.classList.toggle("bg-primary", i === index);
      indicator.classList.toggle("bg-white", i !== index);
    });

    this.currentIndex = index;
  }

  nextItem() {
    const nextIndex = (this.currentIndex + 1) % this.items.length;
    this.showItem(nextIndex);
  }

  prevItem() {
    const prevIndex =
      (this.currentIndex - 1 + this.items.length) % this.items.length;
    this.showItem(prevIndex);
  }

  startAutoplay() {
    if (this.autoplayInterval) return;
    this.autoplayInterval = setInterval(
      () => this.nextItem(),
      this.options.autoplayInterval
    );
  }

  pauseAutoplay() {
    if (this.autoplayInterval) {
      clearInterval(this.autoplayInterval);
      this.autoplayInterval = null;
    }
  }

  destroy() {
    this.pauseAutoplay();
  }
}

const avatarButton = document.getElementById("avatarButton");
const avatarDropdown = document.getElementById("avatarDropdown");

let currentPage = 1;
const itemsPerPage = 10;
let totalItems = 0;
let totalPages = 0;
let benefitsData = [];

async function fetchNewsData() {
  try {
    const response = await fetch(`${ROOT}public/Home/fetchHomeNews`);
    const result = await response.json();
    return result.data;
  } catch (error) {
    return [];
  }
}

function trimPath(imageUrl) {
  if (!imageUrl) return ROOT + "public/assets/images/default-news.jpg";

  return imageUrl.includes("public/")
    ? ROOT + imageUrl.replace("../public", "public/")
    : ROOT + imageUrl;
}

function createNewsItem(item, index) {
  const imageUrl = item.thumbnail_url
    ? trimPath(item.thumbnail_url)
    : ROOT + "public/assets/images/default-news.jpg";

  return `
          <div class="${index === 0 ? "md:col-span-2" : ""} relative group">
            <div class="relative h-80 overflow-hidden rounded-lg shadow-lg">
              <img src="${imageUrl}" 
                   alt="${item.titre}" 
                   class="w-full h-full object-cover"
                   >
              <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent">
                <div class="absolute bottom-6 left-6 right-6 text-white">
                  <h3 class="${
                    index === 0 ? "text-2xl" : "text-lg"
                  } font-bold mb-3">${item.titre}</h3>
                  <div class="flex justify-between items-center">
                    <span class="${
                      index === 0 ? "text-sm" : "text-xs"
                    }">${new Date(
    item.date_publication
  ).toLocaleDateString()}</span>
                  </div>
                  <a href="${ROOT}public/Home/article/${
    item.id
  }" class="mt-4 bg-secondary hover:bg-secondary-hover text-white px-4 py-2 rounded-full flex items-center">
                    <span>Lire la suite</span>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                  </a>
                </div>
              </div>
            </div>
          </div>
        `;
}

async function showNewsSection() {
  const data = await fetchNewsData();
  const newsSection = document.querySelector(".news-section");
  if (!newsSection) return;

  newsSection.innerHTML = `
        <div class="bg-background-light p-8 max-w-6xl mx-auto">
          <h2 class="text-2xl font-semibold text-primary mb-8">Publications récentes</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            ${data.map((item, index) => createNewsItem(item, index)).join("")}
          </div>
        </div>
      `;
}

async function fetchBenefitsData() {
  try {
    const response = await fetch(`${ROOT}public/Home/fetchMemberBenefits`);
    const result = await response.json();
    if (result.status === "success") {
      benefitsData = result.data;
      totalItems = result.data.length;
      totalPages = Math.ceil(totalItems / itemsPerPage);

      const start = 0;
      const end = Math.min(itemsPerPage, totalItems);
      const firstPageData = benefitsData.slice(start, end);
      updateTable(firstPageData);
      updatePaginationInfo(start + 1, end, totalItems);
      updatePagination();
    }
  } catch (error) {
    console.error("Erreur lors de la récupération des avantages :", error);
  }
}

function updateTable(data) {
  const tableBody = document.getElementById("benefitsTableBody");
  if (!tableBody) return;

  tableBody.innerHTML = data
    .map(
      (item, index) => `
        <tr class="border-b hover:bg-background-light transition-colors duration-200 ${
          index % 2 === 0 ? "bg-white" : "bg-gray-50"
        }">
            <td class="px-6 py-4 font-medium text-text-primary">
                ${item.nom || ""}
            </td>
            <td class="px-6 py-4 text-text-secondary">${
              item.type_offre || ""
            }</td>
            <td class="px-6 py-4 font-semibold text-state-success">${
              item.valeur || ""
            }</td>
            <td class="px-6 py-4 max-w-xs overflow-hidden overflow-ellipsis line-clamp-2 text-text-secondary">
                ${item.description || ""}
            </td>
        </tr>
    `
    )
    .join("");
}

function updatePagination() {
  const pagination = document.getElementById("pagination");
  pagination.innerHTML = `
          <li>
              <button data-action="previous" class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 ${
                currentPage === 1 ? "cursor-not-allowed opacity-50" : ""
              }" ${currentPage === 1 ? "disabled" : ""}>
                  <span class="sr-only">Précédent</span>
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                  </svg>
              </button>
          </li>`;

  for (let i = 1; i <= totalPages; i++) {
    pagination.innerHTML += `
              <li>
                  <button data-action="page" data-page="${i}" class="flex items-center justify-center px-3 py-2 text-sm leading-tight ${
      currentPage === i
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
                currentPage === totalPages
                  ? "cursor-not-allowed opacity-50"
                  : ""
              }" ${currentPage === totalPages ? "disabled" : ""}>
                  <span class="sr-only">Suivant</span>
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                  </svg>
              </button>
          </li>`;
}

function changePage(page) {
  if (page >= 1 && page <= totalPages) {
    currentPage = page;
    const start = (page - 1) * itemsPerPage;
    const end = Math.min(start + itemsPerPage, totalItems);
    const paginatedData = benefitsData.slice(start, end);
    updateTable(paginatedData);
    updatePaginationInfo(start + 1, end, totalItems);
    updatePagination();
  }
}

function updatePaginationInfo(start, end, total) {
  document.getElementById("startIndex").textContent = start;
  document.getElementById("endIndex").textContent = end;
  document.getElementById("totalItems").textContent = total;
}

async function fetchPartnersLogos() {
  try {
    const response = await fetch(`${ROOT}public/Home/fetchPartnerLogos`);
    const result = await response.json();
    if (result.status === "success") {
      const logosContainer = document.querySelector('[x-ref="logos"]');
      if (logosContainer) {
        logosContainer.innerHTML = result.data
          .map(
            (partner) => `
          <li>
            <img src="${trimPath(partner.logo)}" 
                 alt="${partner.nom}" 
                 class="w-32 h-16 object-contain"
                >
          </li>
        `
          )
          .join("");
      }
    }
  } catch (error) {
    console.error("Erreur lors de la récupération des logos :", error);
  }
}

async function fetchCarouselData() {
  try {
    const response = await fetch(`${ROOT}public/Home/fetchCarouselData`);
    const result = await response.json();

    if (result.status === "success" && result.data.length > 0) {
      const carouselContainer = document.querySelector(
        '[data-carousel="slide"] .overflow-hidden'
      );
      const indicatorsContainer = document.querySelector(
        '[data-carousel="slide"] .rtl\\:space-x-reverse'
      );

      if (!carouselContainer || !indicatorsContainer) return;

      carouselContainer.innerHTML = result.data
        .map(
          (slide, index) => `
        <div class="hidden duration-3000 ease-in-out" data-carousel-item>
          ${
            slide.link
              ? `<a href="${slide.link}" class="block">`
              : '<div class="block">'
          }
            <img src="${trimPath(slide.src)}" 
                 class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 object-cover h-full" 
                 alt="${slide.alt}"
                 onerror="this.src='${ROOT}public/assets/images/default-news.jpg'">
            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-6">
              <h3 class="text-white text-xl font-bold">${slide.title}</h3>
              <span class="text-white/80 text-sm">${
                slide.type === "news" ? "Actualité" : "Offre spéciale"
              }</span>
            </div>
          ${slide.link ? "</a>" : "</div>"}
        </div>
      `
        )
        .join("");

      indicatorsContainer.innerHTML = result.data
        .map(
          (_, index) => `
        <button type="button" 
                class="w-3 h-3 rounded-full bg-white" 
                aria-current="${index === 0 ? "true" : "false"}" 
                aria-label="Slide ${index + 1}" 
                data-carousel-slide-to="${index}">
        </button>
      `
        )
        .join("");

      return new Carousel(document.querySelector('[data-carousel="slide"]'), {
        autoplayInterval: 3000,
        pauseOnHover: true,
      });
    }
  } catch (error) {
    console.error("Error initializing carousel:", error);
  }
  return null;
}

function initializeEventListeners() {
  if (avatarButton && avatarDropdown) {
    avatarButton.addEventListener("click", function () {
      avatarDropdown.classList.toggle("hidden");
    });

    document.addEventListener("click", function (event) {
      if (
        !avatarButton.contains(event.target) &&
        !avatarDropdown.contains(event.target)
      ) {
        avatarDropdown.classList.add("hidden");
      }
    });
  } else {
  }

  showNewsSection();
  changePage(1);

  const pagination = document.getElementById("pagination");
  if (pagination) {
    pagination.addEventListener("click", (e) => {
      const target = e.target.closest("button");
      if (!target) return;

      if (target.hasAttribute("data-action")) {
        const action = target.getAttribute("data-action");
        if (action === "previous" && currentPage > 1) {
          changePage(currentPage - 1);
        } else if (action === "next" && currentPage < totalPages) {
          changePage(currentPage + 1);
        } else if (action === "page") {
          const page = parseInt(target.getAttribute("data-page"));
          changePage(page);
        }
      }
    });
  }
  fetchBenefitsData();
  fetchPartnersLogos();

  fetchCarouselData().then((carousel) => {
    if (carousel) {
    }
  });
}

document.addEventListener("DOMContentLoaded", () => {
  initializeEventListeners();
  fetchCarouselData();
  showNewsSection();
  fetchBenefitsData();
  fetchPartnersLogos();
});
