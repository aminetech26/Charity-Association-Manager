<div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px" id="tabs">
        <li class="me-2">
            <a href="#partenaires" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 tab-link active" aria-current="page">Partenaires</a>
        </li>
        <li class="me-2">
            <a href="#compte-partenaires" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 tab-link">Comptes partenaires</a>
        </li>
        <li class="me-2">
            <a href="#offres" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 tab-link">Offres</a>
        </li>
        <li class="me-2">
            <a href="#statistiques" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 tab-link">Statistiques</a>
        </li>
    </ul>
</div>

<div id="tab-content">
    <div id="partenaires" class="tab-pane py-4">
        <div class="relative bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
            <div class="flex flex-col items-center justify-between p-4 space-y-3 md:flex-row md:space-y-0 md:space-x-4">
                <div class="w-full md:w-1/2">
                    <form class="flex items-center">
                        <label for="simple-search" class="sr-only">Rechercher par nom</label>
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" id="simple-search" class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Rechercher par nom" required="">
                        </div>
                    </form>
                </div>
                <div class="flex flex-col items-stretch justify-end flex-shrink-0 w-full space-y-2 md:w-auto md:flex-row md:space-y-0 md:items-center md:space-x-3">
                    <button type="button" id="btnAjouterPartenaire" class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-primary hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                        <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                        </svg>
                        Ajouter Partenaire
                    </button>
                    <button id="deleteAllButton" class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg md:w-auto focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:ring-4 focus:ring-gray-200 dark:text-gray-400 dark:bg-gray-800 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                        Supprimer la sélection
                    </button>
                    <div class="relative">
    <!-- Filters Button -->
    <button id="filterButton" class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg md:w-auto focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:ring-4 focus:ring-gray-200 dark:text-gray-400 dark:bg-gray-800 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2 text-gray-400" viewbox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
        </svg>
        Filtres
    </button>
    <!-- Dropdown -->
    <div id="filterDropdown" class="z-10 hidden px-3 pt-1 bg-white rounded-lg shadow w-80 dark:bg-gray-700 absolute top-full right-0 mt-1">
        <div class="flex items-center justify-between pt-2">
            <h6 class="text-sm font-medium text-black dark:text-white">Filtres</h6>
            <div class="flex items-center space-x-3">
                <a href="#" id="clearAllFilters" class="flex items-center text-sm font-medium text-primary-600 dark:text-primary-500 hover:underline">Supprimer les filtres</a>
            </div>
        </div>
        <div class="pt-3 pb-2">
            <!-- Ville Filter -->
            <div class="mb-4">
                <label for="filterVille" class="block text-sm font-medium text-gray-900 dark:text-white mb-1">Ville</label>
                <div class="relative">
                    <input type="text" id="filterVille" class="block w-full p-2 pl-3 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Entrer une ville">
                    <button id="clearVille" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <!-- Category Filter -->
            <div class="mb-4">
                <label for="filterCategorie" class="block text-sm font-medium text-gray-900 dark:text-white mb-1">Catégorie</label>
                <div class="relative">
                    <input type="text" id="filterCategorie" class="block w-full p-2 pl-3 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Entrer une catégorie">
                    <button id="clearCategorie" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                        </div>
                    </div>
                </div>
            </div>
                </div>
            </div>
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                <input type="checkbox" id="selectAll" class="w-4 h-4">
                            </th>
                            <th scope="col" class="px-6 py-3">Nom</th>
                            <th scope="col" class="px-6 py-3">Ville</th>
                            <th scope="col" class="px-6 py-3">Catégorie</th>
                            <th scope="col" class="px-6 py-3">Contact</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="partnersTableBody">
                        <!-- Filled dynamically -->
                    </tbody>
                </table>
            </div>
            <nav class="flex flex-col items-start justify-between p-4 space-y-3 md:flex-row md:items-center md:space-y-0" aria-label="Table navigation">
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    Affichage <span class="font-semibold text-gray-900 dark:text-white" id="startIndex">1</span>-<span class="font-semibold text-gray-900 dark:text-white" id="endIndex">10</span> sur <span class="font-semibold text-gray-900 dark:text-white" id="totalItems">100</span>
                </span>
                <ul class="inline-flex items-stretch -space-x-px" id="pagination">
                    <!-- Filled dynamically -->
                </ul>
            </nav>
        </div>
    </div>
</div>
    <div id="compte-partenaires" class="tab-pane p-4 hidden">
        <h2 class="text-xl font-bold mb-4">Gestion des comptes partenaires</h2>
        <p>Ceci est le contenu de la section Gestion des comptes partenaires.</p>
    </div>
    <div id="offres" class="tab-pane p-4 hidden">
        <h2 class="text-xl font-bold mb-4">Gestion des Offres</h2>
        <p>Ceci est le contenu de la section Offres.</p>
    </div>
    <div id="statistiques" class="tab-pane p-4 hidden">
        <h2 class="text-xl font-bold mb-4">Statistiques</h2>
        <p>Ceci est le contenu de la section Statistiques.</p>
    </div>
</div>
<script src="<?= ROOT ?>admin/assets/js/partenaire_section.js"></script>