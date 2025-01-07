<div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px" id="tabs">
        <li class="me-2">
            <a href="#membres" class="inline-block p-4 border-b-2  rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 tab-link active" aria-current="page">Membres</a>
        </li>
        <li class="me-2">
            <a href="#inscription" class="inline-block p-4 border-b-2  rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 tab-link">Inscriptions</a>
        </li>
    </ul>
</div>
<div id="tab-content">
    <div id="membres" class="tab-pane p-4 hidden">
        <h2 class="text-xl font-bold mb-4">Gestion des membres</h2>
        <p>Ceci est le contenu de la section membres.</p>
    </div>
    <div id="inscription" class="tab-pane p-4 hidden">
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
            <button type="button" id="filterButton" class="flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg md:w-auto focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:ring-4 focus:ring-gray-200 dark:text-gray-400 dark:bg-gray-800 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2 text-gray-400" viewbox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                </svg>
                Filtres
            </button>
        </div>
    </div>
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        <input type="checkbox" id="selectAll" class="w-4 h-4">
                    </th>
                    <th scope="col" class="px-6 py-3">ID</th>
                    <th scope="col" class="px-6 py-3">Photo</th>
                    <th scope="col" class="px-6 py-3">Nom</th>
                    <th scope="col" class="px-6 py-3">Prénom</th>
                    <th scope="col" class="px-6 py-3">Reçu de paiement</th>
                    <th scope="col" class="px-6 py-3">Pièce d'identité</th>
                    <th scope="col" class="px-6 py-3">Email</th>
                    <th scope="col" class="px-6 py-3">Adresse</th>
                    <th scope="col" class="px-6 py-3">Téléphone</th>
                    <th scope="col" class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody id="membersTableBody">
            </tbody>
        </table>
    </div>
    <nav class="flex flex-col items-start justify-between p-4 space-y-3 md:flex-row md:items-center md:space-y-0" aria-label="Table navigation">
        <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
            Affichage <span class="font-semibold text-gray-900 dark:text-white" id="startIndex">1</span>-<span class="font-semibold text-gray-900 dark:text-white" id="endIndex">10</span> sur <span class="font-semibold text-gray-900 dark:text-white" id="totalItems">100</span>
        </span>
        <ul class="inline-flex items-stretch -space-x-px" id="pagination">
        </ul>
    </nav>
</div>
    </div>
</div>
<script src="<?= ROOT ?>admin/assets/js/membres_section.js"></script>