<div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200">
    <ul class="flex flex-wrap -mb-px" id="tabs">
        <li class="me-2">
            <a href="#general" class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 tab-link active" aria-current="page">Informations Générales</a>
        </li>
        <li class="me-2">
            <a href="#carte" class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 tab-link">Carte Abonné</a>
        </li>
    </ul>
</div>

<div id="tab-content">
    <div id="general" class="tab-pane p-4">
        <h2 class="text-xl font-bold mb-4">Informations Générales du Membre</h2>
        <form class="max-w-2xl mx-auto" id="memberForm">
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
        <div class="flex justify-center items-center min-h-[60vh]">
            <div id="no-subscription-message" class="hidden max-w-md w-full p-4 bg-yellow-50 rounded-lg text-center">
                <p class="text-yellow-800">Vous devez avoir un abonnement actif pour accéder à votre carte de membre.</p>
                <a href="<?= ROOT ?>public/Membre/subscription_content" class="inline-block mt-2 text-blue-600 hover:underline">
                    Souscrire à un abonnement
                </a>
            </div>

            <div id="member-card" class="hidden max-w-md w-full bg-white rounded-lg shadow-lg overflow-hidden h-[320px]">
                <div class="bg-secondary p-3 flex items-center justify-between border-b border-blue-100">
                    <div class="flex items-center space-x-3">
                        <img id="association-logo" src="" alt="Logo Association" class="w-12 h-12 object-contain">
                        <p class="text-lg font-semibold text-white">El Mountada</p>
                    </div>
                    <h2 class="text-xl font-bold text-white">Carte Abonné</h2>
                </div>
                
                <div class="p-4 flex flex-col justify-between h-[calc(100%-64px)]">
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <p class="text-gray-500">ID Membre</p>
                            <p class="font-semibold" id="card-member-id"></p>
                        </div>
                        <div>
                            <p class="text-gray-500">Nom et Prénom</p>
                            <p class="font-semibold" id="card-member-name"></p>
                        </div>
                        <div>
                            <p class="text-gray-500">Type d'Abonnement</p>
                            <p class="font-semibold text-blue-600" id="card-subscription-type"></p>
                        </div>
                        <div>
                            <p class="text-gray-500">Expire le</p>
                            <p class="font-semibold text-red-600" id="card-expiry-date"></p>
                        </div>
                    </div>

                    <div class="flex justify-center py-2 border-t border-gray-100">
                        <div id="qrcode" class="flex justify-center items-center">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= ROOT ?>public/assets/js/profile_content.js"></script>