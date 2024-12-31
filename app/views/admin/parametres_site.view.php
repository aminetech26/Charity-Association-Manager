<div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px" id="tabs">
        <li class="me-2">
            <a href="#administrateurs" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 tab-link active" aria-current="page">Comptes administrateurs</a>
        </li>
        <li class="me-2">
            <a href="#theme" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 tab-link">Thèmes et styles</a>
        </li>
    </ul>
</div>
<div id="tab-content">
    <div id="administrateurs" class="tab-pane p-4 hidden">
        <h2 class="text-xl font-bold mb-4">Gestion des comptes d'admin et super-admin</h2>
        <p>Ceci est le contenu de la section d'admin et super-admin.</p>
    </div>
    <div id="theme" class="tab-pane p-4 hidden">
        <h2 class="text-xl font-bold mb-4">Gestion des paramètres de styles et affichage</h2>
        <p>Ceci est le contenu de la section de styles et affichage.</p>
    </div>
</div>
<script src="<?= ROOT ?>admin/assets/js/membres_section.js"></script>