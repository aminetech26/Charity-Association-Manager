document.addEventListener("DOMContentLoaded", function () {
  const ROOT = "http://localhost/TDWProject/public/";
  const categories = ["Hôtels", "Cliniques", "Écoles", "Agences de Voyage"];
  const sampleData = {
    Hôtels: [
      {
        name: "Hotel A",
        city: "City A",
        discount: "10%",
        details: {
          email: "hotelA@example.com",
          address: "123 Street A",
          phone: "123-456-7890",
          website: "http://hotelA.com",
          logo: ROOT + "assets/images/logo.png",
        },
      },
      {
        name: "Hotel B",
        city: "City B",
        discount: "15%",
        details: {
          email: "hotelB@example.com",
          address: "456 Street B",
          phone: "123-456-7891",
          website: "http://hotelB.com",
          logo: ROOT + "assets/images/logo.png",
        },
      },
      {
        name: "Hotel B",
        city: "City B",
        discount: "15%",
        details: {
          email: "hotelB@example.com",
          address: "456 Street B",
          phone: "123-456-7891",
          website: "http://hotelB.com",
          logo: ROOT + "assets/images/logo.png",
        },
      },
      {
        name: "Hotel B",
        city: "City B",
        discount: "15%",
        details: {
          email: "hotelB@example.com",
          address: "456 Street B",
          phone: "123-456-7891",
          website: "http://hotelB.com",
          logo: ROOT + "assets/images/logo.png",
        },
      },
      {
        name: "Hotel B",
        city: "City B",
        discount: "15%",
        details: {
          email: "hotelB@example.com",
          address: "456 Street B",
          phone: "123-456-7891",
          website: "http://hotelB.com",
          logo: ROOT + "assets/images/logo.png",
        },
      },
      {
        name: "Hotel B",
        city: "City B",
        discount: "15%",
        details: {
          email: "hotelB@example.com",
          address: "456 Street B",
          phone: "123-456-7891",
          website: "http://hotelB.com",
          logo: ROOT + "assets/images/logo.png",
        },
      },
    ],
    Cliniques: [
      {
        name: "Clinic A",
        city: "City A",
        discount: "20%",
        details: {
          email: "clinicA@example.com",
          address: "789 Street A",
          phone: "123-456-7892",
          website: "http://clinicA.com",
          logo: ROOT + "assets/images/logo.png",
        },
      },
    ],
    Écoles: [
      {
        name: "School A",
        city: "City A",
        discount: "5%",
        details: {
          email: "schoolA@example.com",
          address: "101 Street A",
          phone: "123-456-7893",
          website: "http://schoolA.com",
          logo: ROOT + "assets/images/logo.png",
        },
      },
    ],
    "Agences de Voyage": [
      {
        name: "Travel Agency A",
        city: "City A",
        discount: "25%",
        details: {
          email: "travelA@example.com",
          address: "202 Street A",
          phone: "123-456-7894",
          website: "http://travelA.com",
          logo: ROOT + "assets/images/logo.png",
        },
      },
    ],
  };

  const partnersContainer = document.getElementById("partnersContainer");
  const citySearch = document.getElementById("citySearch");
  const categoryTabs = document.getElementById("categoryTabs");
  const pagination = document.getElementById("pagination");
  const startIndex = document.getElementById("startIndex");
  const endIndex = document.getElementById("endIndex");
  const totalItems = document.getElementById("totalItems");

  let currentPage = 1;
  const partnersPerPage = 6;
  let currentCategory = categories[0];
  let filteredData = sampleData[currentCategory];

  categories.forEach((category) => {
    const tab = document.createElement("button");
    tab.textContent = category;
    tab.className =
      "px-4 py-2 bg-primary text-white rounded hover:bg-secondary-dark";
    tab.addEventListener("click", () => {
      currentCategory = category;
      filterPartners();
    });
    categoryTabs.appendChild(tab);
  });

  function displayPartners(data, page) {
    partnersContainer.innerHTML = "";
    const start = (page - 1) * partnersPerPage;
    const end = start + partnersPerPage;
    const paginatedData = data.slice(start, end);

    paginatedData.forEach((partner) => {
      const card = document.createElement("div");
      card.className =
        "bg-white rounded-lg shadow-md p-6 hover:shadow-lg hover:bg-gray-100 transition-shadow transform hover:scale-105";
      card.innerHTML = `
                <img src="${partner.details.logo}" alt="${partner.name}" class="w-24 h-24 mx-auto mb-4 rounded-full">
                <h3 class="text-xl font-bold text-primary text-center">${partner.name}</h3>
                <p class="text-text-secondary text-center mt-2"><strong>Ville:</strong> ${partner.city}</p>
                <p class="text-text-secondary text-center"><strong>Remise:</strong> ${partner.discount}</p>
                <button onclick="showPartnerDetails('${partner.name}', '${partner.city}', '${partner.details.email}', '${partner.details.address}', '${partner.details.phone}', '${partner.details.website}', '${partner.details.logo}')" class="mt-4 bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark w-full">
                    Plus de détails
                </button>
            `;
      partnersContainer.appendChild(card);
    });

    startIndex.textContent = start + 1;
    endIndex.textContent = Math.min(end, data.length);
    totalItems.textContent = data.length;
    renderPagination(data.length);
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
        displayPartners(filteredData, currentPage);
      });
      pagination.appendChild(button);
    }
  }

  citySearch.addEventListener("input", (e) => {
    filterPartners();
  });

  function filterPartners() {
    const searchTerm = citySearch.value.toLowerCase();
    const categoryData = sampleData[currentCategory];
    filteredData = categoryData.filter((partner) =>
      partner.city.toLowerCase().includes(searchTerm)
    );
    currentPage = 1;
    displayPartners(filteredData, currentPage);
  }

  filterPartners();
});
