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

// News section

const newsData = [
  {
    title:
      "Les grandes banques obtiennent un répit face à la règle Volcker de l'ère de crise",
    author: "Don Howard",
    readTime: "99,7 %",
    image: "/placeholder.jpg",
  },
  {
    title:
      "Comment un contrat avec le Pentagone est devenu une crise identitaire pour Google",
    author: "Lauren Gregory",
    readTime: "98,7 %",
    image: "/placeholder.jpg",
  },
  {
    title:
      "Les comédies sur la maternité s'accrochent à la réalité brute de la nouvelle maternité",
    author: "Charlie Bell",
    readTime: "99,7 %",
    image: "/placeholder.jpg",
  },
  {
    title: "Pourquoi vous devriez arrêter d'être si dur avec vous-même",
    author: "Craig Estrada",
    readTime: "99,7 %",
    image: "/placeholder.jpg",
  },
  {
    title: "Pusha-T, un conférencier à la recherche d'une cible, en trouve une",
    author: "Henry Larson",
    readTime: "99,1 %",
    image: "/placeholder.jpg",
  },
];

async function fetchNewsData() {
  try {
    const response = await fetch("");
    return await response.json();
  } catch (error) {
    console.error("Erreur lors de la récupération des actualités :", error);
    return newsData;
  }
}

function createNewsItem(item, index) {
  return `
          <div class="${index === 0 ? "md:col-span-2" : ""} relative group">
            <div class="relative h-80 overflow-hidden rounded-lg shadow-lg">
              <img src="${item.image}" alt="${
    item.title
  }" class="w-full h-full object-cover">
              <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent">
                <div class="absolute bottom-6 left-6 right-6 text-white">
                  <h3 class="${
                    index === 0 ? "text-2xl" : "text-lg"
                  } font-bold mb-3">${item.title}</h3>
                  <div class="flex justify-between items-center">
                    <span class="${index === 0 ? "text-sm" : "text-xs"}">${
    item.author
  }</span>
                    <span class="${index === 0 ? "text-sm" : "text-xs"}">${
    item.readTime
  }</span>
                  </div>
                  <button class="mt-4 bg-secondary hover:bg-secondary-hover text-white px-4 py-2 rounded-full flex items-center">
                    <span>Lire la suite</span>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                  </button>
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

// Section avantages

const benefitsData = [
  {
    partenaire: "Amazon",
    type_offre: "Réduction",
    valeur: "10%",
    description:
      "Profitez d'une réduction de 10% sur tous les produits Amazon Prime.",
  },
  {
    partenaire: "Netflix",
    type_offre: "Essai gratuit",
    valeur: "1 mois",
    description: "Bénéficiez d'un mois d'essai gratuit sur Netflix.",
  },
  {
    partenaire: "Spotify",
    type_offre: "Abonnement premium",
    valeur: "3 mois gratuits",
    description: "Profitez de 3 mois d'abonnement premium gratuit sur Spotify.",
  },
  {
    partenaire: "Uber",
    type_offre: "Code promo",
    valeur: "5€ de réduction",
    description:
      "Utilisez ce code promo pour obtenir 5€ de réduction sur votre prochaine course Uber.",
  },
  {
    partenaire: "Airbnb",
    type_offre: "Crédit voyage",
    valeur: "25€",
    description:
      "Recevez un crédit voyage de 25€ pour votre première réservation Airbnb.",
  },
  {
    partenaire: "Deliveroo",
    type_offre: "Réduction",
    valeur: "20%",
    description:
      "Obtenez 20% de réduction sur votre première commande Deliveroo.",
  },
  {
    partenaire: "Apple",
    type_offre: "Cadeau",
    valeur: "AirPods",
    description:
      "Recevez des AirPods gratuits lors de l'achat d'un nouvel iPhone.",
  },
  {
    partenaire: "Google",
    type_offre: "Crédit",
    valeur: "50€",
    description:
      "Bénéficiez d'un crédit de 50€ à utiliser sur le Google Store.",
  },
  {
    partenaire: "Microsoft",
    type_offre: "Essai gratuit",
    valeur: "1 mois",
    description: "Profitez d'un mois d'essai gratuit sur Microsoft 365.",
  },
  {
    partenaire: "Adobe",
    type_offre: "Réduction",
    valeur: "30%",
    description:
      "Obtenez 30% de réduction sur votre abonnement Adobe Creative Cloud.",
  },
];

let currentPage = 1;
const itemsPerPage = 10;
let totalItems = benefitsData.length;
let totalPages = Math.ceil(totalItems / itemsPerPage);

function updateTable(data) {
  const tableBody = document.getElementById("benefitsTableBody");
  tableBody.innerHTML = data
    .map(
      (item, index) => `
            <tr class="border-b hover:bg-background-light transition-colors duration-200 ${
              index % 2 === 0 ? "bg-white" : "bg-gray-50"
            }">
                <td class="px-6 py-4 font-medium text-text-primary">
                    ${item.partenaire}
                </td>
                <td class="px-6 py-4 text-text-secondary">${
                  item.type_offre
                }</td>
                <td class="px-6 py-4 font-semibold text-state-success">${
                  item.valeur
                }</td>
                <td class="px-6 py-4 max-w-xs overflow-hidden overflow-ellipsis line-clamp-2 text-text-secondary">
                    ${item.description}
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
    const end = start + itemsPerPage;
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

function initializeEventListeners() {
  // Initialisation du carrousel
  const carouselElement = document.querySelector('[data-carousel="slide"]');
  if (carouselElement) {
    const carousel = new Carousel(carouselElement, {
      autoplayInterval: 3000,
      pauseOnHover: true,
    });
  }

  // Affichage de la section des actualités
  showNewsSection();

  // Affichage de la section des avantages

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
  // Affichage des logos des partenaires

  renderPartnersLogos();
}

// partners logos section

const partnersData = [
  {
    src: "https://upload.wikimedia.org/wikipedia/commons/thumb/0/05/Facebook_Logo_%282019%29.png/1024px-Facebook_Logo_%282019%29.png",
    alt: "Facebook",
  },
  {
    src: "https://upload.wikimedia.org/wikipedia/commons/thumb/3/3e/Disney%2B_logo.svg/2560px-Disney%2B_logo.svg.png",
    alt: "Disney",
  },
  {
    src: "https://upload.wikimedia.org/wikipedia/commons/thumb/6/69/Airbnb_Logo_B%C3%A9lo.svg/2560px-Airbnb_Logo_B%C3%A9lo.svg.png",
    alt: "Airbnb",
  },
  {
    src: "https://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/Apple_logo_black.svg/488px-Apple_logo_black.svg.png",
    alt: "Apple",
  },
  {
    src: "https://upload.wikimedia.org/wikipedia/commons/thumb/3/33/Vanamo_Logo.png/600px-Vanamo_Logo.png",
    alt: "Spark",
  },
  {
    src: "https://upload.wikimedia.org/wikipedia/commons/thumb/2/24/Samsung_Logo.svg/2560px-Samsung_Logo.svg.png",
    alt: "Samsung",
  },
  {
    src: "https://upload.wikimedia.org/wikipedia/commons/thumb/9/91/Quora_logo_2015.svg/250px-Quora_logo_2015.svg.png",
    alt: "Quora",
  },
  {
    src: "https://upload.wikimedia.org/wikipedia/commons/thumb/9/96/Sass_Logo_Color.svg/1280px-Sass_Logo_Color.svg.png",
    alt: "Sass",
  },
];

function renderPartnersLogos() {
  const logosContainer = document.querySelector('[x-ref="logos"]');
  if (!logosContainer) return;

  logosContainer.innerHTML = partnersData
    .map(
      (partner) => `
                <li>
                    <img src="${partner.src}" alt="${partner.alt}" class="w-32 h-16 object-contain" />
                </li>
            `
    )
    .join("");
}

document.addEventListener("DOMContentLoaded", initializeEventListeners);
