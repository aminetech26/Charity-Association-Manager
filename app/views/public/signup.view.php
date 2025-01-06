<?php
class Signup_view
{
    use View;

    public function showSignUpForm()
    {
        ?>
<div class="bg-blue-100 min-h-screen flex items-center justify-center py-6">
<div class="container mx-auto px-4">
            <div class="bg-white rounded-xl shadow-2xl p-6 max-w-4xl mx-auto">
              <h1 class="text-2xl font-bold text-center mb-6 bg-gradient-to-r from-primary to-primary-hover bg-clip-text text-transparent">Rejoignez Notre Communauté
              </h1>
              <div class="mb-8">
                <div class="flex justify-between mb-3">
                  <span class="text-xs font-semibold inline-block py-1.5 px-3 rounded-full text-white bg-primary shadow-sm transition-all duration-300 ease-in-out" id="step1">
                    Informations Personnelles
                  </span>
                  <span class="text-xs font-semibold inline-block py-1.5 px-3 rounded-full text-gray-500 bg-gray-200 transition-all duration-300 ease-in-out" id="step2">
                    Documents
                  </span>
                  <span class="text-xs font-semibold inline-block py-1.5 px-3 rounded-full text-gray-500 bg-gray-200 transition-all duration-300 ease-in-out" id="step3">
                    Détails du Compte
                  </span>
                </div>
                <div class="overflow-hidden h-1.5 mb-4 text-xs flex rounded-full bg-gray-200">
                  <div id="progress-bar"
                    class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-primary w-1/3 transition-all duration-500 ease-in-out rounded-full">
                  </div>
                </div>
              </div>
              <form id="multi-step-form" class="space-y-6">
                <div id="step-1" class="step space-y-4">
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label for="nom" class="block mb-1 text-sm font-medium text-gray-900">Nom</label>
                      <input type="text" id="nom" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 transition-colors" required
                        onchange="validateField(this, /^[a-zA-ZÀ-ÿ\s]{2,}$/)">
                      <p class="mt-1 text-xs text-red-500 hidden"></p>
                    </div>
                    <div>
                      <label for="prenom" class="block mb-1 text-sm font-medium text-gray-900">Prénom</label>
                      <input type="text" id="prenom" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 transition-colors" required
                        onchange="validateField(this, /^[a-zA-ZÀ-ÿ\s]{2,}$/)">
                      <p class="mt-1 text-xs text-red-500 hidden"></p>
                    </div>
                  </div>
                  <div>
                    <label for="phone" class="block mb-1 text-sm font-medium text-gray-900">Numéro de Téléphone</label>
                    <input type="tel" id="phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 transition-colors" required
                      onchange="validateField(this, /^[\d\s+()-]{10,}$/)">
                    <p class="mt-1 text-xs text-red-500 hidden"></p>
                  </div>
                  <div>
                    <label for="adresse" class="block mb-1 text-sm font-medium text-gray-900">Adresse</label>
                    <input type="text" id="adresse" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 transition-colors" required
                      onchange="validateField(this, /^.{5,}$/)">
                    <p class="mt-1 text-xs text-red-500 hidden"></p>
                  </div>
                </div>
                <div id="step-2" class="step hidden space-y-4">
                  <div class="mb-4">
                    <span class="block mb-1 text-sm font-medium text-gray-900">Photo Personnelle</span>
                    <div class="flex justify-center items-center w-full">
                        <label for="personal-photo" class="flex flex-col justify-center items-center w-full h-32 bg-gray-50 rounded-lg border-2 border-gray-300 border-dashed cursor-pointer hover:bg-gray-100 transition-colors group">
                            <div class="flex flex-col justify-center items-center p-4">
                                <svg class="w-8 h-8 mb-2 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="text-sm text-gray-500 text-center"><span class="font-semibold">Cliquez</span> ou glissez</p>
                                <p class="text-xs text-gray-500">PNG, JPG (MAX. 2MB)</p>
                            </div>
                            <input id="personal-photo" type="file" class="hidden" required onchange="validateFile(this)">
                            <span class="text-sm text-red-500 hidden">Une photo est requise</span>
                        </label>
                    </div>
                    <p class="mt-1 text-xs text-red-500 hidden"></p>
                  </div>

                  <div class="mb-4">
                    <span class="block mb-1 text-sm font-medium text-gray-900">Pièce d'Identité</span>
                    <div class="flex justify-center items-center w-full">
                        <label for="id-photo" class="flex flex-col justify-center items-center w-full h-32 bg-gray-50 rounded-lg border-2 border-gray-300 border-dashed cursor-pointer hover:bg-gray-100 transition-colors group">
                            <div class="flex flex-col justify-center items-center p-4">
                                <svg class="w-8 h-8 mb-2 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="text-sm text-gray-500 text-center"><span class="font-semibold">Cliquez</span> ou glissez</p>
                                <p class="text-xs text-gray-500">PNG, JPG (MAX. 2MB)</p>
                            </div>
                            <input id="id-photo" type="file" class="hidden" required onchange="validateFile(this)">
                            <span class="text-sm text-red-500 hidden">Une photo est requise</span>
                        </label>
                    </div>
                    <p class="mt-1 text-xs text-red-500 hidden"></p>
                  </div>

                  <div>
                    <div class="text-xs text-gray-600 p-3 bg-gray-50 rounded-lg mb-2">
                      Pour bénéficier des remises, joignez le reçu de paiement (optionnel).
                    </div>
                    <div class="flex justify-center items-center w-full">
                        <label for="payment-receipt" class="flex flex-col justify-center items-center w-full h-32 bg-gray-50 rounded-lg border-2 border-gray-300 border-dashed cursor-pointer hover:bg-gray-100 transition-colors group">
                            <div class="flex flex-col justify-center items-center p-4">
                                <svg class="w-8 h-8 mb-2 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="text-sm text-gray-500 text-center"><span class="font-semibold">Cliquez</span> ou glissez</p>
                                <p class="text-xs text-gray-500">PNG, JPG, PDF (MAX. 5MB)</p>
                            </div>
                            <input id="payment-receipt" type="file" class="hidden" onchange="validateFile(this)">
                        </label>
                    </div>
                  </div>
                </div>
                <div id="step-3" class="step hidden space-y-4">
                  <div>
                    <label for="email" class="block mb-1 text-sm font-medium text-gray-900">Email</label>
                    <input type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 transition-colors" required
                      onchange="validateField(this, /^[^\s@]+@[^\s@]+\.[^\s@]+$/)">
                    <p class="mt-1 text-xs text-red-500 hidden"></p>
                  </div>
                  <div>
                    <label for="password" class="block mb-1 text-sm font-medium text-gray-900">Mot de Passe</label>
                    <input type="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 transition-colors" required
                      onchange="validatePassword(this)">
                    <p class="mt-1 text-xs text-red-500 hidden"></p>
                  </div>
                  <div>
                    <label for="confirmPassword" class="block mb-1 text-sm font-medium text-gray-900">Confirmer le Mot de Passe</label>
                    <input type="password" id="confirmPassword" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 transition-colors" required
                      onchange="validatePasswordConfirm(this)">
                    <p class="mt-1 text-xs text-red-500 hidden"></p>
                  </div>
                </div>
                <div class="flex justify-between mt-8">
                  <button type="button" id="prevBtn" class="hidden px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors">
                    <span class="flex items-center">
                      <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                      </svg>
                      Précédent
                    </span>
                  </button>
                  <button type="button" id="nextBtn" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-colors">
                    <span class="flex items-center">
                      Suivant
                      <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                      </svg>
                    </span>
                  </button>
                  <button type="submit" id="submitBtn" class="hidden px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                    <span class="flex items-center">
                      Créer mon compte
                      <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                      </svg>
                    </span>
                  </button>
                </div>
                </form>
            </div>
            </div>
        </div>
        <script src="<?= ROOT ?>public/assets/js/signup.js"></script>
        </body>
        </html>
        <?php
    }
}