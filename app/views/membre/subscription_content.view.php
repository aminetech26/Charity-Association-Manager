<div class="max-w-4xl mx-auto p-4">
    <div id="subscription-info" class="mb-8">
        <div id="has-subscription" class="hidden">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-bold mb-6 text-gray-900">Détails de votre Abonnement</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600">Type d'Abonnement</p>
                        <p class="text-lg font-semibold text-blue-600" id="type-abonnement"></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Statut</p>
                        <p class="text-lg font-semibold" id="statut-abonnement"></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Date de début</p>
                        <p class="text-lg font-semibold" id="date-debut"></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Date d'expiration</p>
                        <p class="text-lg font-semibold text-red-600" id="date-fin"></p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-600">Temps restant</p>
                        <p class="text-lg font-semibold text-green-600" id="temps-restant"></p>
                    </div>
                </div>
            </div>
        </div>

        <div id="no-subscription" class="hidden bg-yellow-50 rounded-lg p-6 text-center">
            <h2 class="text-xl font-bold text-yellow-800 mb-2">Vous n'avez pas d'abonnement actif</h2>
            <p class="text-yellow-700 mb-4">Souscrivez à un abonnement pour profiter des remises et avantages offerts par nos partenaires.</p>
        </div>
    </div>

    <div class="text-center mb-8">
        <button id="show-subscription-form" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
            <span id="btn-text">Souscrire à un abonnement</span>
        </button>
    </div>

    <div id="subscription-form" class="hidden">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-bold mb-6 text-gray-900">Formulaire d'Abonnement</h3>
            <form id="abonnement-form" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type d'Abonnement</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none hover:border-blue-500">
                            <input type="radio" name="type_abonnement" value="JEUNE" class="sr-only subscription-type" required>
                            <div class="flex flex-1">
                                <div class="flex flex-col">
                                    <span class="block text-sm font-medium text-gray-900">JEUNE</span>
                                    <span class="mt-1 flex items-center text-sm text-gray-500">Pour les - de 25 ans</span>
                                </div>
                            </div>
                            <div class="subscription-border absolute inset-0 rounded-lg border-2 border-transparent pointer-events-none" aria-hidden="true"></div>
                        </label>
                        <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none hover:border-blue-500">
                            <input type="radio" name="type_abonnement" value="CLASSIQUE" class="sr-only subscription-type" required>
                            <div class="flex flex-1">
                                <div class="flex flex-col">
                                    <span class="block text-sm font-medium text-gray-900">CLASSIQUE</span>
                                    <span class="mt-1 flex items-center text-sm text-gray-500">Abonnement standard</span>
                                </div>
                            </div>
                            <div class="subscription-border absolute inset-0 rounded-lg border-2 border-transparent pointer-events-none" aria-hidden="true"></div>
                        </label>
                        <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none hover:border-blue-500">
                            <input type="radio" name="type_abonnement" value="PREMIUM" class="sr-only subscription-type" required>
                            <div class="flex flex-1">
                                <div class="flex flex-col">
                                    <span class="block text-sm font-medium text-gray-900">PREMIUM</span>
                                    <span class="mt-1 flex items-center text-sm text-gray-500">Accès privilégié</span>
                                </div>
                            </div>
                            <div class="subscription-border absolute inset-0 rounded-lg border-2 border-transparent pointer-events-none" aria-hidden="true"></div>
                        </label>
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reçu de Paiement</label>
                    <div class="flex justify-center items-center w-full">
                        <label for="recu" class="flex flex-col justify-center items-center w-full h-32 bg-gray-50 rounded-lg border-2 border-gray-300 border-dashed cursor-pointer hover:bg-gray-100 transition-colors">
                            <div class="flex flex-col justify-center items-center pt-5 pb-6">
                                <svg class="w-10 h-10 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Cliquez</span> ou glissez un fichier</p>
                                <p class="text-xs text-gray-500">PDF ou Image (MAX. 10MB)</p>
                            </div>
                            <input id="recu" name="recu_paiement" type="file" class="hidden" accept=".jpg,.jpeg,.png,.pdf" required/>
                        </label>
                    </div>
                    <div id="file-name" class="mt-2 text-sm text-gray-500"></div>
                </div>

                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" id="cancel-subscription" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        Soumettre
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= ROOT ?>public/assets/js/subscription_content.js"></script>
