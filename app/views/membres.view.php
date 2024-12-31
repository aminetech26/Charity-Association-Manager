<div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px" id="tabs">
        <li class="me-2">
            <a href="#membres" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 tab-link active" aria-current="page">Membres</a>
        </li>
        <li class="me-2">
            <a href="#inscription" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 tab-link">Inscriptions</a>
        </li>
    </ul>
</div>
<div id="tab-content">
    <div id="membres" class="tab-pane p-4 hidden">
        <h2 class="text-xl font-bold mb-4">Gestion des membres</h2>
        <p>Ceci est le contenu de la section membres.</p>
    </div>
    <div id="inscription" class="tab-pane p-4 hidden">
        <h2 class="text-xl font-bold mb-4">Gestion des inscriptions</h2>
        <p>Ceci est le contenu de la section inscriptions.</p>
    </div>
</div>
<script src="<?= ROOT ?>admin/assets/js/membres_section.js"></script>