{
    const ROOT = 'http://localhost/TDWProject/';
    const tabs = document.querySelectorAll('.tab-link');
    const tabPanes = document.querySelectorAll('.tab-pane');


    tabs.forEach(tab => {
        tab.addEventListener('click', function (e) {
            e.preventDefault();

            tabs.forEach(t => t.classList.remove('active', 'text-blue-600', 'border-blue-600'));
            tabPanes.forEach(pane => pane.classList.add('hidden'));

            this.classList.add('active', 'text-blue-600', 'border-blue-600');
            const target = this.getAttribute('href');
            document.querySelector(target).classList.remove('hidden');
        });
    });

    if (tabs.length > 0) {
        tabs[0].classList.add('text-blue-600', 'border-blue-600');
        document.querySelector(tabs[0].getAttribute('href')).classList.remove('hidden');
    }

    // -----------------------------------------------------------------------------------
let currentPage = 1;
const itemsPerPage = 10;
let totalItems = 0;
let totalPages = 1;

// Initialize the page
initializeEventListeners();
loadPartnersFromBackend(currentPage, itemsPerPage);

function initializeEventListeners() {

    document.getElementById('filterButton').addEventListener('click', () => {
        const dropdown = document.getElementById('filterDropdown');
        dropdown.classList.toggle('hidden');
    });

    // Search input listener
    document.getElementById('simple-search').addEventListener('input', (e) => {
        const searchTerm = e.target.value;
        currentPage = 1;
        loadPartnersFromBackend(currentPage, itemsPerPage, searchTerm);
    });

    // Filter listeners
    document.getElementById('filterVille').addEventListener('input', applyFilters);
    document.getElementById('filterCategorie').addEventListener('input', applyFilters);

    // Select all checkbox
    document.getElementById('selectAll').addEventListener('change', (e) => {
        const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
        checkboxes.forEach(checkbox => checkbox.checked = e.target.checked);
    });

    // Delete selected button
    document.getElementById('deleteAllButton').addEventListener('click', deleteSelected);

    // Clear filter buttons
    document.getElementById('clearVille').addEventListener('click', () => {
        document.getElementById('filterVille').value = null;
        applyFilters();
    });

    document.getElementById('clearCategorie').addEventListener('click', () => {
        document.getElementById('filterCategorie').value = null;
        applyFilters();
    });

    document.getElementById('clearAllFilters').addEventListener('click', () => {
        document.getElementById('filterVille').value = null;
        document.getElementById('filterCategorie').value = null;
        applyFilters();
    });
}

function deleteSelected() {
    const selectedCheckboxes = document.querySelectorAll('tbody input[type="checkbox"]:checked');
    if (selectedCheckboxes.length === 0) {
        alert('Veuillez sélectionner au moins un partenaire à supprimer');
        return;
    }

    if (confirm(`Êtes-vous sûr de vouloir supprimer ${selectedCheckboxes.length} partenaire(s) ?`)) {
        const selectedIds = Array.from(selectedCheckboxes).map(checkbox => 
            parseInt(checkbox.getAttribute('data-id'))
        );

        // Send a request to the backend to delete the selected partners
        fetch(`${ROOT}admin/Admin/deletePartners`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ ids: selectedIds }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Reload the partners after deletion
                loadPartnersFromBackend(currentPage, itemsPerPage);
            } else {
                console.error('Error deleting partners:', data.message);
            }
        })
        .catch(error => {
            console.error('Error deleting partners:', error);
        });
    }
}

function applyFilters() {
    const villeFilter = document.getElementById('filterVille').value;
    const categorieFilter = document.getElementById('filterCategorie').value;
    currentPage = 1;
    if(villeFilter === '' && categorieFilter === '') {
        loadPartnersFromBackend(currentPage, itemsPerPage);
    } else {
    loadPartnersFromBackend(currentPage, itemsPerPage, null, villeFilter, categorieFilter);
    }
}

function updateTable(partners) {
    const tableBody = document.getElementById('partnersTableBody');
    tableBody.innerHTML = partners.map(partner => `
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
            <td class="px-6 py-4">
                <input type="checkbox" class="w-4 h-4" data-id="${partner.id}">
            </td>
            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                ${partner.nom}
            </td>
            <td class="px-6 py-4">${partner.ville}</td>
            <td class="px-6 py-4">${partner.categorie_id}</td>
            <td class="px-6 py-4">${partner.email}</td>
            <td class="px-6 py-4 text-right">
                <button onclick="editPartner(${partner.id})" class="font-medium text-blue-600 dark:text-blue-500 hover:underline mr-3">
                    Modifier
                </button>
                <button onclick="deletePartner(${partner.id})" class="font-medium text-red-600 dark:text-red-500 hover:underline">
                    Supprimer
                </button>
            </td>
        </tr>
    `).join('');

    updatePaginationInfo();
}

function updatePaginationInfo(totalItems) {
    const start = Math.min((currentPage - 1) * itemsPerPage + 1, totalItems);
    const end = Math.min(currentPage * itemsPerPage, totalItems);

    document.getElementById('startIndex').textContent = start;
    document.getElementById('endIndex').textContent = end;
    document.getElementById('totalItems').textContent = totalItems;
}

function updatePagination() {
    const pagination = document.getElementById('pagination');
    pagination.innerHTML = '';

    // Previous Button
    pagination.innerHTML += `
        <li>
            <button onclick="changePage(${currentPage - 1})" class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 ${currentPage === 1 ? 'cursor-not-allowed opacity-50' : ''}" ${currentPage === 1 ? 'disabled' : ''}>
                <span class="sr-only">Précédent</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </li>
    `;

    // Page Numbers
    for (let i = 1; i <= totalPages; i++) {
        pagination.innerHTML += `
            <li>
                <button onclick="changePage(${i})" class="flex items-center justify-center px-3 py-2 text-sm leading-tight ${currentPage === i ? 'text-blue-600 bg-blue-50 border border-blue-300' : 'text-gray-500 bg-white border border-gray-300'} hover:bg-gray-100">
                    ${i}
                </button>
            </li>
        `;
    }

    // Next Button
    pagination.innerHTML += `
        <li>
            <button onclick="changePage(${currentPage + 1})" class="flex items-center justify-center h-full py-1.5 px-3 text-gray-500 bg-white rounded-r-lg border border-gray-300 hover:bg-gray-100 ${currentPage === totalPages ? 'cursor-not-allowed opacity-50' : ''}" ${currentPage === totalPages ? 'disabled' : ''}>
                <span class="sr-only">Suivant</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </li>
    `;
}

function changePage(page) {
    if (page >= 1 && page <= totalPages) {
        currentPage = page;
        loadPartnersFromBackend(page, itemsPerPage);
    }
}

async function loadPartnersFromBackend(page = 1, limit = 10, searchTerm = '', ville = '', categorie_id = '') {
    try {
        const response = await fetch(`${ROOT}admin/Admin/getAllPartners?page=${page}&limit=${limit}&nom=${searchTerm}&ville=${ville}&categorie_id=${categorie_id}`);
        const data = await response.json();
        if (data.status === 'success') {
            updateTable(data.data);
            totalItems = data.pagination.total;
            totalPages = data.pagination.total_pages;
            updatePagination();
            updatePaginationInfo(data.pagination.total); // Pass totalItems to updatePaginationInfo
        } else {
            console.error('Error loading partners:', data.message);
        }
    } catch (error) {
        console.error('Error loading partners:', error);
    }
}
}