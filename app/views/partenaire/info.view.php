<div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px" id="tabs">
        <li class="me-2">
            <a href="#general" class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 tab-link active" aria-current="page">Informations Générales</a>
        </li>
        <li class="me-2">
            <a href="#offres" class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 tab-link">Offres Proposées</a>
        </li>
    </ul>
</div>
<div id="tab-content">
    <div id="general" class="tab-pane p-4 hidden">
        <h2 class="text-xl font-bold mb-4">Informations Générales du Partenaire</h2>
        <form class="max-w-2xl mx-auto">
            <div class="grid gap-4 mb-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <div class="flex items-center justify-center w-full mb-4">
                        <img id="partner-logo" src="" alt="Logo Partenaire" class="w-32 h-32 rounded-lg object-cover border-2 border-gray-200">
                    </div>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-900">Nom</label>
                    <input type="text" id="nom" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" readonly>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-900">Ville</label>
                    <input type="text" id="ville" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" readonly>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-900">Email de Contact</label>
                    <input type="email" id="email" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" readonly>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-900">Numéro de Téléphone</label>
                    <input type="tel" id="telephone" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" readonly>
                </div>
                <div class="sm:col-span-2">
                    <label class="block mb-1 text-sm font-medium text-gray-900">Adresse</label>
                    <input type="text" id="adresse" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" readonly>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-900">Site Web</label>
                    <input type="url" id="site_web" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" readonly>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-900">Statut</label>
                    <input type="text" id="statut" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" readonly>
                </div>
                <div class="sm:col-span-2">
                    <label class="block mb-1 text-sm font-medium text-gray-900">Catégorie</label>
                    <input type="text" id="categorie" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" readonly>
                </div>
            </div>
        </form>
    </div>
    <div id="offres" class="tab-pane p-4 hidden">
        <h2 class="text-xl font-bold mb-4">Offres du Partenaire</h2>
        <div class="relative bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">ID</th>
                            <th scope="col" class="px-6 py-3">Type Offre</th>
                            <th scope="col" class="px-6 py-3">Valeur</th>
                            <th scope="col" class="px-6 py-3">Description</th>
                            <th scope="col" class="px-6 py-3">Date de début</th>
                            <th scope="col" class="px-6 py-3">Date de fin</th>
                            <th scope="col" class="px-6 py-3">Est spécial</th>
                            <th scope="col" class="px-6 py-3">Thumbnail</th>
                        </tr>
                    </thead>
                    <tbody id="OffersTableBody">
                    </tbody>
                </table>
            </div>
            <nav class="flex flex-col items-start justify-between p-4 space-y-3 md:flex-row md:items-center md:space-y-0" aria-label="Table navigation">
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    Affichage <span class="font-semibold text-gray-900 dark:text-white" id="startIndexOffres">1</span>-<span class="font-semibold text-gray-900 dark:text-white" id="endIndexOffres">10</span> sur <span class="font-semibold text-gray-900 dark:text-white" id="totalItemsOffres">100</span>
                </span>
                <ul class="inline-flex items-stretch -space-x-px" id="paginationOffres">
                </ul>
            </nav>
        </div>
</div>
<script src="<?= ROOT ?>public/assets/js/info_content.js"></script>