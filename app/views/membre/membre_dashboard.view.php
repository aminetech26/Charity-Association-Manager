<?php
class Membre_dashboard_view
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
                <a href="<?= ROOT ?>public/Membre/dashboard" class="flex ms-2 md:me-24">
                  <img src="<?= ROOT ?>public/assets/images/logo.png" class="h-8 me-3" alt="Logo">
                  <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap">Espace Membre</span>
                </a>
              </div>
              <div class="flex items-center gap-4">
                <div class="relative">
                  <button type="button" class="relative flex items-center p-2 text-gray-500 rounded-lg hover:bg-gray-100" id="notifications-menu-button">
                    <span class="sr-only">View notifications</span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span id="notification-badge" class="hidden absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">0</span>
                  </button>
                  <div id="notifications-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg z-50 max-h-96 overflow-y-auto">
                    <div class="p-4 border-b border-gray-200">
                      <h3 class="text-lg font-semibold text-gray-900">Notifications</h3>
                    </div>
                    <div id="notifications-list" class="divide-y divide-gray-200">
                    </div>
                  </div>
                </div>
                <div class="relative">
                  <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300" id="user-menu-button">
                    <img class="w-8 h-8 rounded-full object-cover" src="<?= ROOT.'public/'.$_SESSION['membre_photo']?>" alt="user photo">
                  </button>
                  <div class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-50" id="user-dropdown">
                    <div class="px-4 py-3 border-b">
                      <p class="text-sm text-gray-900"><?= $_SESSION['membre_nom'] . ' ' . $_SESSION['membre_prenom'] ?>
                      </p>
                      <p class="text-sm font-medium text-gray-900"><?= $_SESSION['membre_email'] ?></p>
                    </div>
                    <ul>
                      <li>
                        <a href="<?= ROOT ?>public/Membre/signOut" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Déconnexion</a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </nav>
        <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white flex flex-col">
        <ul class="space-y-2 font-medium">
            <?php 
            $menu_items = [
                ['icon' => 'https://api.iconify.design/mdi:account.svg', 'text' => 'Mes coordonnées', 'href' => 'profile', 'target' => 'profile_content'],
                ['icon' => 'https://api.iconify.design/mdi:card-account-details.svg', 'text' => 'Info abonnement', 'href' => 'subscription', 'target' => 'subscription_content'],
                ['icon' => 'https://api.iconify.design/mdi:heart.svg', 'text' => 'Partenaires favoris', 'href' => 'favorites', 'target' => 'favorites_content'],
                ['icon' => 'https://api.iconify.design/mdi:hand-heart.svg', 'text' => 'Bénévoler', 'href' => 'volunteer', 'target' => 'volunteer_content'],
                ['icon' => 'https://api.iconify.design/mdi:history.svg', 'text' => 'Historique d\'activité', 'href' => 'history', 'target' => 'history_content'],
                ['icon' => 'https://api.iconify.design/mdi:charity.svg', 'text' => 'Faire un don', 'href' => 'donate', 'target' => 'donate_content'],
                ['icon' => 'https://api.iconify.design/mdi:cash.svg', 'text' => 'Demander une aide', 'href' => 'assistance', 'target' => 'assistance_content'],
                ['icon' => 'https://api.iconify.design/mdi:comment-text.svg', 'text' => 'Soumettre un feedback', 'href' => 'feedback', 'target' => 'feedback_content'],
            ];
            
            foreach ($menu_items as $item): ?>
                <li>
                    <a href="<?= $item['href'] ?>" data-target="<?= $item['target'] ?>" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-200 group menu-item">
                        <img src="<?= $item['icon'] ?>" alt="<?=$item['text'] ?>" class="w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900">
                        <span class="ml-3"><?= $item['text'] ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="mt-auto">
            <ul class="space-y-2 font-medium">
                <li>
                    <a href="<?= ROOT ?>public/Membre/signOut" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-200 group">
                        <img src="<?= ROOT . 'admin/assets/images/logout.png' ?>" alt="Déconnexion" class="w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900">
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
                    <h1 class="text-4xl font-bold text-primary mb-4">Bienvenue dans votre Espace Membre</h1>
                    <p class="text-lg text-text-secondary mb-8">Gérez votre profil et accédez à tous nos services depuis votre tableau de bord.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-primary p-6 rounded-lg shadow-md">
                            <h2 class="text-xl font-semibold text-white mb-2">Mon Profil</h2>
                            <p class="text-gray-200">Consultez et mettez à jour vos informations personnelles.</p>
                        </div>
                        <div class="bg-primary p-6 rounded-lg shadow-md">
                            <h2 class="text-xl font-semibold text-white mb-2">Mon Abonnement</h2>
                            <p class="text-gray-200">Gérez votre abonnement et ses avantages.</p>
                        </div>
                        <div class="bg-primary p-6 rounded-lg shadow-md">
                            <h2 class="text-xl font-semibold text-white mb-2">Mes Partenaires</h2>
                            <p class="text-gray-200">Accédez à vos partenaires favoris et leurs services.</p>
                        </div>
                        <div class="bg-primary p-6 rounded-lg shadow-md">
                            <h2 class="text-xl font-semibold text-white mb-2">Bénévolat</h2>
                            <p class="text-gray-200">Participez aux événements en tant que bénévole.</p>
                        </div>
                        <div class="bg-primary p-6 rounded-lg shadow-md">
                            <h2 class="text-xl font-semibold text-white mb-2">Dons et Aides</h2>
                            <p class="text-gray-200">Faites un don ou demandez une aide selon vos besoins.</p>
                        </div>
                        <div class="bg-primary p-6 rounded-lg shadow-md">
                            <h2 class="text-xl font-semibold text-white mb-2">Feedback</h2>
                            <p class="text-gray-200">Partagez votre expérience et vos suggestions.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>    
        <script src="<?= ROOT ?>public/assets/js/membre_dashboard.js"></script>
        <?php }
}