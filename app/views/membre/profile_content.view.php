<div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200">
    <ul class="flex flex-wrap -mb-px" id="tabs">
        <li class="me-2">
            <a href="#general" class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 tab-link active" aria-current="page">Informations Générales</a>
        </li>
        <li class="me-2">
            <a href="#carte" class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 tab-link">Carte de Membre</a>
        </li>
    </ul>
</div>

<div id="tab-content">
    <!-- Tab Informations Générales -->
    <div id="general" class="tab-pane p-4">
        <h2 class="text-xl font-bold mb-4">Informations Générales du Membre</h2>
        <form class="max-w-2xl mx-auto" id="memberForm">
            <!-- Informations non modifiables -->
            <div class="grid gap-4 mb-6 sm:grid-cols-2">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-900">Identifiant Unique</label>
                    <input type="text" id="member_id" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" readonly>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-900">Date de Création</label>
                    <input type="text" id="date_creation" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" readonly>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-900">Nom</label>
                    <input type="text" id="nom" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" readonly>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-900">Prénom</label>
                    <input type="text" id="prenom" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" readonly>
                </div>
            </div>

            <!-- Informations modifiables -->
            <div class="grid gap-4 mb-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="block mb-1 text-sm font-medium text-gray-900">Mot de Passe</label>
                    <input type="password" id="password" name="mot_de_passe" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-900">Email</label>
                    <input type="email" id="email" name="email" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-900">Numéro de Téléphone</label>
                    <input type="tel" id="telephone" name="numero_de_telephone" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                </div>
                <div class="sm:col-span-2">
                    <label class="block mb-1 text-sm font-medium text-gray-900">Adresse</label>
                    <input type="text" id="adresse" name="adresse" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                </div>
                <div class="sm:col-span-2">
                    <button type="submit" id="updateButton" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Mettre à jour les informations
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div id="carte" class="tab-pane p-4 hidden">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                <img id="association-logo" src="" alt="Logo Association" class="w-24 h-24 object-contain">
                <p>Association El Mountada</p>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Carte de Membre</h2>
            </div>
            
            <div class="border-2 border-gray-200 rounded-lg p-6 mb-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Identifiant Membre:</p>
                        <p class="text-lg font-semibold" id="card-member-id"></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nom et Prénom:</p>
                        <p class="text-lg font-semibold" id="card-member-name"></p>
                    </div>
                </div>
            </div>

            <div class="flex justify-center">
                <div id="qrcode"></div>
            </div>
        </div>
    </div>
</div>
<script src="<?= ROOT ?>public/assets/js/profile_content.js"></script>