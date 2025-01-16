<?php
class Admin_dashboard_view
{
    use AdminView;
    public function show_dashboard_page() { ?>
        <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200">
          <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
              <div class="flex items-center justify-start">
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                  <span class="sr-only">Open sidebar</span>
                  <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path></svg>
                </button>
                <a href="<?= ROOT ?>admin/Admin/dashboard" class="flex ms-2 md:me-24">
                  <img src="<?= ROOT ?>admin/assets/images/logo.png" class="h-8 me-3" alt="Logo">
                  <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap">Admin Panel</span>
                </a>
              </div>
              <div class="flex items-center">
                <div class="flex items-center ms-3">
                  <div class="relative">
                    <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300" id="user-menu-button">
                      <img class="w-8 h-8 rounded-full object-cover" src="<?= ROOT ?>admin/assets/images/<?= $_SESSION['admin_role'] ?>.png" alt="user photo">
                    </button>
                    <div class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-50" id="user-dropdown">
                      <div class="px-4 py-3 border-b">
                        <p class="text-sm text-gray-900"><?= $_SESSION['admin_nom'] ?></p>
                        <p class="text-sm font-medium text-gray-900"><?= $_SESSION['admin_role'] ?></p>
                      </div>
                      <ul>
                        <li>
                          <a href="<?= ROOT ?>admin/Admin/signOut" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Déconnexion</a>
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
            <?php 
            $menu_items = [
                ['icon' => ROOT . 'admin/assets/images/partenaire.png', 'text' => 'Gestion partenaire', 'href' => 'partenaire', 'target' => 'partenaire_content'],
                ['icon' => ROOT . 'admin/assets/images/membre.png', 'text' => 'Gestion des membres', 'href' => 'members', 'target' => 'members_content'],
                ['icon' => ROOT . 'admin/assets/images/don.png', 'text' => 'Gestion des dons', 'href' => 'donations', 'target' => 'donations_content'],
                ['icon' => ROOT . 'admin/assets/images/aide.png', 'text' => 'Gestion d\'Aides', 'href' => 'aides', 'target' => 'aides_content'],
                ['icon' => ROOT . 'admin/assets/images/notification.png', 'text' => 'Gestions des notifications', 'href' => 'notifications', 'target' => 'notifications_content'],
            ];
            
            if ($_SESSION['admin_role'] === 'SUPER_ADMIN') {
                $menu_items[] = ['icon' => ROOT . 'admin/assets/images/parametres.png', 'text' => 'Paramètres site', 'href' => 'settings', 'target' => 'settings_content'];
            }
            
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
                    <a href="<?= ROOT ?>admin/Admin/signOut" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-200 group">
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
                    <h1 class="text-4xl font-bold text-primary mb-4">Bienvenue sur le Panneau d'Administration</h1>
                    <p class="text-lg text-text-secondary mb-8">Gérez votre site efficacement grâce aux sections disponibles dans la barre latérale.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-primary p-6 rounded-lg shadow-md">
                            <h2 class="text-xl font-semibold text-white mb-2">Gestion Partenaire</h2>
                            <p class="text-gray-200">Ajoutez, modifiez et gérez les partenaires de votre association.</p>
                        </div>
                        <div class="bg-primary p-6 rounded-lg shadow-md">
                            <h2 class="text-xl font-semibold text-white mb-2">Gestion des Membres</h2>
                            <p class="text-gray-200">Administrez les membres de votre association.</p>
                        </div>
                        <div class="bg-primary p-6 rounded-lg shadow-md">
                            <h2 class="text-xl font-semibold text-white mb-2">Gestion des Dons</h2>
                            <p class="text-gray-200">Suivez et gérez les dons reçus pour votre cause.</p>
                        </div>
                        <div class="bg-primary p-6 rounded-lg shadow-md">
                            <h2 class="text-xl font-semibold text-white mb-2">Gestion des Notifications</h2>
                            <p class="text-gray-200">Envoyez et gérez les notifications pour vos utilisateurs.</p>
                        </div>
                        <div class="bg-primary p-6 rounded-lg shadow-md">
                            <h2 class="text-xl font-semibold text-white mb-2">Gestion des Groupes</h2>
                            <p class="text-gray-200">Créez et organisez des groupes pour structurer votre association.</p>
                        </div>
                        <div class="bg-primary p-6 rounded-lg shadow-md">
                            <h2 class="text-xl font-semibold text-white mb-2">Paramètres du Site</h2>
                            <p class="text-gray-200">Configurez les paramètres globaux de votre site.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>    
        <script src="<?= ROOT ?>admin/assets/js/admin_dashboard.js"></script>
        <?php }
}