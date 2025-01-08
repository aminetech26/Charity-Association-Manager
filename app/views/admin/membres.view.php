<div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px" id="tabs">
        <li class="me-2">
            <a href="#membres" class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 tab-link active" aria-current="page">Membres</a>
        </li>
        <li class="me-2">
            <a href="#inscription" class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 tab-link">Inscriptions</a>
        </li>
    </ul>
</div>
<div id="tab-content">
    <div id="membres" class="tab-pane p-4 hidden">
        <h2 class="text-xl font-bold mb-4">Gestion des membres</h2>
        <div class="mb-4 flex items-center gap-6">
    <div class="flex items-center">
        <label class="mr-2">Rechercher par nom:</label>
        <input 
            type="text" 
            id="searchMembersByName" 
            placeholder="Rechercher par nom..." 
            class="p-2 border rounded"
        >
    </div>
    <div class="flex items-center">
        <label class="mr-2">Rechercher par date d'inscription:</label>
        <input 
            type="date" 
            id="filterMembersByDate" 
            class="p-2 border rounded"
        >
    </div>
</div>
        <div class="relative bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">ID</th>
                            <th scope="col" class="px-6 py-3">Photo</th>
                            <th scope="col" class="px-6 py-3">Nom</th>
                            <th scope="col" class="px-6 py-3">Prénom</th>
                            <th scope="col" class="px-6 py-3">Pièce d'identité</th>
                            <th scope="col" class="px-6 py-3">Email</th>
                            <th scope="col" class="px-6 py-3">Adresse</th>
                            <th scope="col" class="px-6 py-3">Téléphone</th>
                            <th scope="col" class="px-6 py-3">Type Abonnement</th>
                            <th scope="col" class="px-6 py-3">Date Inscription</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="membersTableBody"></tbody>
                </table>
            </div>
            <nav class="flex flex-col items-start justify-between p-4 space-y-3 md:flex-row md:items-center md:space-y-0" aria-label="Table navigation">
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    Affichage <span class="font-semibold text-gray-900 dark:text-white" id="membersStartIndex">1</span>-<span class="font-semibold text-gray-900 dark:text-white" id="membersEndIndex">10</span> sur <span class="font-semibold text-gray-900 dark:text-white" id="membersTotalItems">100</span>
                </span>
                <ul class="inline-flex items-stretch -space-x-px" id="membersPagination"></ul>
            </nav>
        </div>
    </div>
    <div id="inscription" class="tab-pane p-4 hidden">
    <h2 class="text-xl font-bold mb-4">Gestion des inscriptions</h2>
    <div class="relative bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">ID</th>
                    <th scope="col" class="px-6 py-3">Photo</th>
                    <th scope="col" class="px-6 py-3">Nom</th>
                    <th scope="col" class="px-6 py-3">Prénom</th>
                    <th scope="col" class="px-6 py-3">Pièce d'identité</th>
                    <th scope="col" class="px-6 py-3">Reçu paiement</th>
                    <th scope="col" class="px-8 py-3">Type abonnement</th>
                    <th scope="col" class="px-6 py-3">Email</th>
                    <th scope="col" class="px-6 py-3">Adresse</th>
                    <th scope="col" class="px-6 py-3">Téléphone</th>
                    <th scope="col" class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody id="registrationsTableBody">
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