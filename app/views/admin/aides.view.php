<div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px" id="tabs">
        <li class="me-2">
            <a href="#demande_aide" class="inline-block p-4 border-b-2  rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 tab-link active" aria-current="page">Demande d'aides</a>
        </li>
        <li class="me-2">
            <a href="#type_aides" class="inline-block p-4 border-b-2  rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 tab-link">Type d'aides</a>
        </li>
    </ul>
</div>
<div id="tab-content">
    <div id="demande_aide" class="tab-pane p-4 hidden">
        <h2 class="text-xl font-bold mb-4">Gestion des demandes d'aides</h2>
        <div class="relative bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">ID</th>
                            <th scope="col" class="px-6 py-3">Nom</th>
                            <th scope="col" class="px-6 py-3">Prénom</th>
                            <th scope="col" class="px-6 py-3">Date de Naissance</th>
                            <th scope="col" class="px-6 py-3">Type d'aide</th>
                            <th scope="col" class="px-6 py-3">Dossier soumis</th>
                            <th scope="col" class="px-6 py-3">Description</th>
                            <th scope="col" class="px-6 py-3">Date de demande</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="demandesTableBody">
                    </tbody>
                </table>
            </div>
            <nav class="flex flex-col items-start justify-between p-4 space-y-3 md:flex-row md:items-center md:space-y-0" aria-label="Table navigation">
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    Affichage <span class="font-semibold text-gray-900 dark:text-white" id="startIndexDemandes">1</span>-<span class="font-semibold text-gray-900 dark:text-white" id="endIndexDemandes">10</span> sur <span class="font-semibold text-gray-900 dark:text-white" id="totalItemsDemandes">100</span>
                </span>
                <ul class="inline-flex items-stretch -space-x-px" id="paginationDemandes">
                </ul>
            </nav>
        </div>
    </div>
    <div id="type_aides" class="tab-pane p-4 hidden">
        <h2 class="text-xl font-bold mb-4">Types d'aides</h2>
        <div class="relative bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
        <div class="flex flex-col items-center justify-between p-4 space-y-3 md:flex-row md:space-y-0 md:space-x-4">
            <div class="flex flex-col items-stretch justify-end flex-shrink-0 w-full space-y-2 md:w-auto md:flex-row md:space-y-0 md:items-center md:space-x-3">
                <button type="button" id="btnCreerTypeAide" class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-primary hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                    <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                    </svg>
                    Créer un nouveau type d'aides
                </button>
            </div>
        </div>
        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">ID</th>
                        <th scope="col" class="px-6 py-3">Nom</th>
                        <th scope="col" class="px-6 py-3">Dossier requis</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody id="typeAidesTableBody">
                </tbody>
            </table>
        </div>
        <nav class="flex flex-col items-start justify-between p-4 space-y-3 md:flex-row md:items-center md:space-y-0" aria-label="Table navigation">
            <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                Affichage <span class="font-semibold text-gray-900 dark:text-white" id="startIndexTypeAides">1</span>-<span class="font-semibold text-gray-900 dark:text-white" id="endIndexTypeAides">10</span> sur <span class="font-semibold text-gray-900 dark:text-white" id="totalItemsTypeAides">100</span>
            </span>
            <ul class="inline-flex items-stretch -space-x-px" id="paginationTypeAides">
            </ul>
        </nav>
        <div id="createTypeAidesModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] md:h-full bg-gray-900 bg-opacity-50">
    <div class="relative p-4 w-full max-w-3xl h-full md:h-auto">
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Créer un nouveau type d'aide</h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="createTypeAidesModal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    <span class="sr-only">Fermer</span>
                </button>
            </div>
            <form action="#">
    <div class="grid gap-4 mb-4">
        <div>
            <label for="label" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Label</label>
            <input type="text" name="label" id="label" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Entrez le label" required>
        </div>
        
        <div>
            <label for="dossier_requis" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Dossier requis</label>
            <div class="relative">
                <textarea 
                    id="dossier_requis" 
                    name="dossier_requis" 
                    rows="4" 
                    class="block p-2.5 pl-8 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="Entrez les documents requis (un par ligne)&#10;☐ Extrait de naissance&#10;☐ Carte d'identité&#10;☐ Justificatif de domicile"
                ></textarea>
                <div class="absolute text-gray-400 text-sm top-3 left-3 select-none pointer-events-none">
                </div>
            </div>
        </div>

        <div>
            <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
            <textarea 
                id="description" 
                name="description" 
                rows="4" 
                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                placeholder="Entrez la description"
            ></textarea>
        </div>
    </div>

    <div class="items-center space-y-4 sm:flex sm:space-y-0 sm:space-x-4">
        <button type="submit" class="w-full sm:w-auto justify-center text-white inline-flex bg-primary hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Enregistrer</button>
        <button data-modal-toggle="createTypeAidesModal" type="button" class="w-full justify-center sm:w-auto text-gray-500 inline-flex items-center bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
            <svg class="mr-1 -ml-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
            Annuler
        </button>
    </div>
</form>
        </div>
    </div>
    </div>
</div>
<script src="<?= ROOT ?>admin/assets/js/aides_section.js"></script>