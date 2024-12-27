<div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px" id="tabs">
        <li class="me-2">
            <a href="#partenaires" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 tab-link active" aria-current="page">Partenaires</a>
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
    <div id="partenaires" class="tab-pane p-4">
        <h2 class="text-xl font-bold mb-4">Gestion des Partenaires</h2>
        <p>Ceci est le contenu pfff de la section Partenaires.</p>
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