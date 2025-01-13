<div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px" id="tabs">
        <li class="me-2">
            <a href="#remise-offertes" class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 tab-link active" aria-current="page">Remise offertes</a>
        </li>
        <li class="me-2">
            <a href="#ajouter-remise" class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 tab-link">Ajouter une remise</a>
        </li>
    </ul>
</div>
<div id="tab-content">
    <div id="remise-offertes" class="tab-pane p-4 hidden">
        <h2 class="text-xl font-bold mb-4">Liste des membres ayant bénéficié d'une offre chez <?= $_SESSION['partenaire_nom'] ?></h2>
        <div class="relative bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Nom</th>
                            <th scope="col" class="px-6 py-3">Prénom</th>
                            <th scope="col" class="px-6 py-3">Offre ID</th>
                            <th scope="col" class="px-6 py-3">Date de bénéfice</th>
                        </tr>
                    </thead>
                    <tbody id="remiseTableBody">
                    </tbody>
                </table>
            </div>
            <nav class="flex flex-col items-start justify-between p-4 space-y-3 md:flex-row md:items-center md:space-y-0" aria-label="Table navigation">
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    Affichage <span class="font-semibold text-gray-900 dark:text-white" id="startIndexRemise">1</span>-<span class="font-semibold text-gray-900 dark:text-white" id="endIndexRemise">10</span> sur <span class="font-semibold text-gray-900 dark:text-white" id="totalItemsRemise">100</span>
                </span>
                <ul class="inline-flex items-stretch -space-x-px" id="paginationRemise">
                </ul>
            </nav>
        </div>
    </div>
    <div id="ajouter-remise" class="tab-pane p-4 hidden">
        <h2 class="text-xl font-bold mb-4">Ajouter un membre comme bénéficiaire d'une offre</h2>
        <form id="addRemiseForm">
            <div class="grid gap-4 mb-4 sm:grid-cols-2">
                <div>
                    <label for="member_unique_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ID unique du membre</label>
                    <input type="text" name="member_unique_id" id="member_unique_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Entrez l'ID unique du membre" required>
                </div>
                <div>
                    <label for="offre_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ID de l'offre</label>
                    <input type="text" name="offre_id" id="offer_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Entrez l'ID de l'offre" required>
                </div>
                <div>
                    <label for="date_benefice" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date de bénéfice</label>
                    <input type="date" name="date_benefice" id="date_benefice" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                </div>
            </div>
            <button type="submit" class="text-white inline-flex items-center bg-primary hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                Ajouter
            </button>
        </form>
    </div>
</div>
<script src="<?= ROOT ?>public/assets/js/remise_content.js"></script>