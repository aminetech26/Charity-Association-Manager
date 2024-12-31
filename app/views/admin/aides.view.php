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
        <p>Ceci est le contenu de la section demandes d'aides.</p>
    </div>
    <div id="type_aides" class="tab-pane p-4 hidden">
        <h2 class="text-xl font-bold mb-4">Types d'aides</h2>
        <p>Ceci est le contenu de la section Types d'aides.</p>
    </div>
</div>
<script src="<?= ROOT ?>admin/assets/js/aides_section.js"></script>