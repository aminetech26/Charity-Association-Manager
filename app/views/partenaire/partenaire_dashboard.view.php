<?php
class Partenaire_dashboard_view
{
    use View;

    public function show_dashboard_page() { ?>
        <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200">
          <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
              <div class="flex items-center justify-start">
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                  <span class="sr-only">Open sidebar</span>
                  <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path></svg>
                </button>
                <a href="<?= ROOT ?>public/Partenaire/dashboard" class="flex ms-2 md:me-24">
                  <img src="<?= ROOT ?>admin/assets/images/logo.png" class="h-8 me-3" alt="Logo">
                  <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap">Espace Partenaire</span>
                </a>
              </div>
              <div class="flex items-center">
                <div class="flex items-center ms-3">
                  <div class="relative">
                    <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300" id="user-menu-button">
                      <img class="w-8 h-8 rounded-full object-cover" src="<?= ROOT ?>admin/assets/images/ADMIN.png" alt="user photo">
                    </button>
                    <div class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-50" id="user-dropdown">
                      <div class="px-4 py-3 border-b">
                        <p class="text-sm text-gray-900"><?= $_SESSION['partenaire_id'] ?></p>
                        <p class="text-sm font-medium text-gray-900"><?= $_SESSION['partenaire_nom'] ?></p>
                      </div>
                      <ul>
                        <li>
                          <a href="<?= ROOT ?>public/Partenaire/logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Déconnexion</a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </nav>

        <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0">
          <div class="h-full px-3 pb-4 overflow-y-auto bg-white flex flex-col">
            <ul class="space-y-2 font-medium">
              <li>
                <a href="info" data-target="info_content" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-200 group menu-item">
                  <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  <span class="ml-3">Info Partenaire</span>
                </a>
              </li>
              <li>
                <a href="verification" data-target="verification_content" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-200 group menu-item">
                  <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  <span class="ml-3">Vérification Membre</span>
                </a>
              </li>
              <li>
                <a href="remise" data-target="remise_content" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-200 group menu-item">
                  <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  <span class="ml-3">Ajouter Remise Membre</span>
                </a>
              </li>
            </ul>

            <div class="mt-auto">
              <ul class="space-y-2 font-medium">
                <li>
                  <a href="<?= ROOT ?>public/Partenaire/logout" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-200 group">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span class="ml-3">Déconnexion</span>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </aside>

        <main class="ml-64 p-20 bg-background-light">
          <div class="flex flex-col items-center justify-center h-full">
            <div class="text-center">
              <h1 class="text-4xl font-bold text-primary mb-4">Bienvenue sur l'Espace Partenaire</h1>
              <p class="text-lg text-text-secondary mb-8">Gérez vos services et vérifications des membres.</p>
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-primary p-6 rounded-lg shadow-md">
                  <h2 class="text-xl font-semibold text-white mb-2">Vérification QR Code</h2>
                  <p class="text-gray-200">Scannez et vérifiez les cartes des membres via QR code.</p>
                </div>
                <div class="bg-primary p-6 rounded-lg shadow-md">
                  <h2 class="text-xl font-semibold text-white mb-2">Verification Manuelle</h2>
                  <p class="text-gray-200">Vérifiez les membres via leur identifiant en cas de problème de lecture QR.</p>
                </div>
                <div class="bg-primary p-6 rounded-lg shadow-md">
                  <h2 class="text-xl font-semibold text-white mb-2">Gestion des Remises</h2>
                  <p class="text-gray-200">Ajoutez et gérez les remises pour les membres.</p>
                </div>
              </div>
            </div>
          </div>
        </main>    
        <script src="<?= ROOT ?>public/assets/js/partenaire_dashboard.js"></script>
    <?php }
}