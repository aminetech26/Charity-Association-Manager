const ROOT = document.querySelector('meta[name="root"]')?.getAttribute('content') || '/TDWProject/';
let currentPage = 1;
let currentFilters = {};

function createOfferCard(offer, isSpecial = false) {
    const imageUrl = offer.thumbnail_path 
        ? `${ROOT}${offer.thumbnail_path.replace('../public/', 'public/')}`
        : `${ROOT}public/assets/images/default-offer.jpg`;

    return `
        <div class="bg-white rounded-lg shadow-lg overflow-hidden ${isSpecial ? 'border-2 border-primary' : ''}">
            <div class="relative">
                <img src="${imageUrl}" 
                     alt="${offer.type_offre}"
                     class="w-full h-48 object-cover"
                     onerror="this.src='${ROOT}public/assets/images/default-offer.jpg'">
                ${isSpecial ? `
                    <div class="absolute top-2 right-2 bg-primary text-white px-3 py-1 rounded-full text-sm">
                        Offre Spéciale
                    </div>
                ` : ''}
            </div>
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-xl font-bold text-gray-900">${offer.partenaire_nom}</h3>
                    <span class="text-lg font-semibold text-primary">${offer.valeur}</span>
                </div>
                <p class="text-gray-600 mb-4">${offer.description}</p>
                <div class="flex justify-between items-center text-sm text-gray-500">
                    <span>Type: ${offer.type_offre}</span>
                    <span>Valide jusqu'au ${new Date(offer.date_fin).toLocaleDateString()}</span>
                </div>
            </div>
        </div>
    `;
}

async function loadOffers(page = 1, filters = {}) {
    try {
        const queryParams = new URLSearchParams({
            page,
            ...filters
        });

        const response = await fetch(`${ROOT}public/Home/fetchOffers?${queryParams}`);
        const result = await response.json();

        if (result.status === 'success') {
            const regularOffersContainer = document.getElementById('regularOffers');
            const specialOffersContainer = document.getElementById('specialOffers');

            if (!regularOffersContainer || !specialOffersContainer) return;

            // Clear containers
            regularOffersContainer.innerHTML = '';
            specialOffersContainer.innerHTML = '';

            // Separate special and regular offers
            result.data.offers.forEach(offer => {
                const container = offer.is_special ? specialOffersContainer : regularOffersContainer;
                container.innerHTML += createOfferCard(offer, offer.is_special);
            });

            updatePagination(result.data.pages, page);
        }
    } catch (error) {
        console.error('Error loading offers:', error);
    }
}

function updatePagination(totalPages, currentPage) {
    const pagination = document.getElementById('pagination');
    if (!pagination) return;

    pagination.innerHTML = `
        <button class="px-3 py-1 rounded-md ${currentPage === 1 ? 'bg-gray-100 text-gray-400' : 'bg-white text-gray-700'}" 
                ${currentPage === 1 ? 'disabled' : ''}
                onclick="changePage(${currentPage - 1})">
            Précédent
        </button>
        ${Array.from({ length: totalPages }, (_, i) => i + 1)
            .map(page => `
                <button class="px-3 py-1 rounded-md ${page === currentPage ? 'bg-primary text-white' : 'bg-white text-gray-700'}"
                        onclick="changePage(${page})">
                    ${page}
                </button>
            `).join('')}
        <button class="px-3 py-1 rounded-md ${currentPage === totalPages ? 'bg-gray-100 text-gray-400' : 'bg-white text-gray-700'}"
                ${currentPage === totalPages ? 'disabled' : ''}
                onclick="changePage(${currentPage + 1})">
            Suivant
        </button>
    `;
}

function changePage(page) {
    currentPage = page;
    loadOffers(page, currentFilters);
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    const filterForm = document.getElementById('filterForm');
    
    if (filterForm) {
        filterForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(filterForm);
            currentFilters = Object.fromEntries(formData);
            currentPage = 1;
            loadOffers(1, currentFilters);
        });
    }

    loadOffers(1);
});
