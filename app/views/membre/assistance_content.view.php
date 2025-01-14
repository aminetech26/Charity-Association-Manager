<div id="demande-aide" class="tab-pane p-4">
    <h2 class="text-2xl font-bold mb-6 text-gray-900">Demande d'Aide</h2>
    <form id="aideForm" class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
        <!-- Nom -->
        <div class="mb-4">
            <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
            <input type="text" id="nom" name="nom" required
                   class="mt-1 block w-full p-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Prénom -->
        <div class="mb-4">
            <label for="prenom" class="block text-sm font-medium text-gray-700">Prénom</label>
            <input type="text" id="prenom" name="prenom" required
                   class="mt-1 block w-full p-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Date de naissance -->
        <div class="mb-4">
            <label for="date_naissance" class="block text-sm font-medium text-gray-700">Date de Naissance</label>
            <input type="date" id="date_naissance" name="date_naissance" required
                   class="mt-1 block w-full p-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Type d'aide (Select) -->
        <div class="mb-4">
            <label for="type_aide" class="block text-sm font-medium text-gray-700">Type d'Aide</label>
            <select id="type_aide" name="type_aide" required
                    class="mt-1 block w-full p-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="" disabled selected>Sélectionnez un type d'aide</option>
                <!-- Les options seront remplies dynamiquement via JavaScript -->
            </select>
        </div>

        <!-- Description -->
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea id="description" name="description" rows="4" required
                      class="mt-1 block w-full p-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
        </div>

        <!-- Fichier ZIP -->
        <div class="mb-6">
            <label for="fichier_zip" class="block text-sm font-medium text-gray-700">Dossier (Format ZIP)</label>
            <input type="file" id="fichier_zip" name="fichier_zip" accept=".zip" required
                   class="mt-1 block w-full p-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Bouton de soumission -->
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                Envoyer la Demande
            </button>
        </div>
    </form>
</div>