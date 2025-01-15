<div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200">
    <ul class="flex flex-wrap -mb-px" id="history-tabs">
        <li class="me-2">
            <a href="#donations" class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 tab-link active">Historique des Dons</a>
        </li>
        <li class="me-2">
            <a href="#volunteering" class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 tab-link">Historique Bénévolat</a>
        </li>
        <li class="me-2">
            <a href="#discounts" class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 tab-link">Remises Obtenues</a>
        </li>
        <li class="me-2">
            <a href="#assistance" class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 tab-link">Demandes d'Aide</a>
        </li>
    </ul>
</div>

<div id="tab-content" class="p-4">
    <div id="donations" class="tab-pane">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Date</th>
                        <th scope="col" class="px-6 py-3">Montant</th>
                        <th scope="col" class="px-6 py-3">Statut</th>
                        <th scope="col" class="px-6 py-3">Reçu</th>
                    </tr>
                </thead>
                <tbody id="donations-table-body"></tbody>
            </table>
        </div>
    </div>

    <div id="volunteering" class="tab-pane hidden">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Événement</th>
                        <th scope="col" class="px-6 py-3">Date Début</th>
                        <th scope="col" class="px-6 py-3">Date Fin</th>
                        <th scope="col" class="px-6 py-3">Statut</th>
                    </tr>
                </thead>
                <tbody id="volunteering-table-body"></tbody>
            </table>
        </div>
    </div>

    <div id="discounts" class="tab-pane hidden">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Date</th>
                        <th scope="col" class="px-6 py-3">Partenaire</th>
                        <th scope="col" class="px-6 py-3">Type d'Offre</th>
                        <th scope="col" class="px-6 py-3">Valeur</th>
                    </tr>
                </thead>
                <tbody id="discounts-table-body"></tbody>
            </table>
        </div>
    </div>

    <div id="assistance" class="tab-pane hidden">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Date</th>
                        <th scope="col" class="px-6 py-3">Type d'Aide</th>
                        <th scope="col" class="px-6 py-3">Description</th>
                        <th scope="col" class="px-6 py-3">Statut</th>
                        <th scope="col" class="px-6 py-3">Documents</th>
                    </tr>
                </thead>
                <tbody id="assistance-table-body"></tbody>
            </table>
        </div>
    </div>
</div>

<script src="<?= ROOT ?>public/assets/js/history_content.js"></script>
