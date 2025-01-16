<?php

trait View {
    public function page_head($page_title) {
        ?>
        <!DOCTYPE html>
        <html lang="fr" class="h-full bg-white">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta name="root" content="<?= ROOT ?>">
            <meta http-equiv="cache-control" content="no-cache" />
            <meta http-equiv="Pragma" content="no-cache" />
            <meta http-equiv="Expires" content="-1" />
            <title><?=$page_title?></title>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
            <link rel="stylesheet" href="<?= ROOT . "public/assets/css/main.css?v=" . time() ?>">        <body class="h-full bg-white">
        <?php
    }

    public function nav_bar($is_logged_in = false, $user_avatar_url = '') {
        ?>
        <nav class="bg-white border-gray-200 dark:bg-gray-900 shadow-md">
            <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl p-4">
            <img src="<?= ROOT ?>public/assets/images/logo.png" class="h-12" alt="Association El Mountada Logo" />

                <a href="<?= ROOT ?>public/Home/index" class="flex items-center space-x-3 rtl:space-x-reverse mx-auto"> <!-- Centrer le logo et le texte -->
                    <span class="self-center text-3xl font-bold whitespace-nowrap text-primary">Association El Mountada</span>
                </a>
                <div class="flex items-center space-x-6 rtl:space-x-reverse">
                    <?php if ($is_logged_in): ?>
                        <div class="relative">
                            <img id="avatarButton" class="w-12 h-12 rounded-full cursor-pointer border-2 border-primary" src="<?= $user_avatar_url ?>" alt="User dropdown">
                            <div id="avatarDropdown" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg dark:bg-gray-700 border border-gray-200 dark:border-gray-600">
                                <a href="<?= ROOT ?>public/Membre/dashboard" class="block px-4 py-3 text-lg text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600">Aller à mon compte</a>
                                <a href="<?= ROOT ?>public/Membre/signOut" class="block px-4 py-3 text-lg text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600">Déconnexion</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?= ROOT ?>public/Home/signup" class="text-lg font-semibold text-primary hover:text-primary-hover transition duration-300">S'inscrire</a>
                        <a href="<?= ROOT ?>public/Home/signin" class="text-lg font-semibold bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary-hover transition duration-300">Se connecter</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
        <nav class="bg-gray-50 dark:bg-gray-700 shadow-sm">
            <div class="max-w-screen-xl px-6 py-4 mx-auto">
                <div class="flex items-center justify-between">
                    <ul class="flex flex-row font-medium mt-0 space-x-10 rtl:space-x-reverse text-lg">
                        <li>
                            <a href="<?= ROOT ?>public/Home/index" class="text-gray-900 dark:text-white hover:text-primary dark:hover:text-primary-hover transition duration-300" aria-current="page">Accueil</a>
                        </li>
                        <li>
                            <a href="<?= ROOT ?>public/Home/news" class="text-gray-900 dark:text-white hover:text-primary dark:hover:text-primary-hover transition duration-300">News</a>
                        </li>
                        <li>
                            <a href="<?= ROOT ?>public/Home/remises_avantages" class="text-gray-900 dark:text-white hover:text-primary dark:hover:text-primary-hover transition duration-300">Remises et avantages</a>
                        </li>
                        <li>
                            <a href="<?= ROOT ?>public/Home/catalogue_partenaire" class="text-gray-900 dark:text-white hover:text-primary dark:hover:text-primary-hover transition duration-300">Catalogue des partenaires</a>
                        </li>
                        <li>
                            <a href="<?= ROOT ?>public/Home/donate" class="text-gray-900 dark:text-white hover:text-primary dark:hover:text-primary-hover transition duration-300">Faire un don</a>
                        </li>
                    </ul>
                    <div class="flex items-center space-x-6">
                        <a href="https://facebook.com" target="_blank" class="text-gray-500 hover:text-primary dark:hover:text-primary-hover transition duration-300">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" />
                            </svg>
                        </a>
                        <a href="https://youtube.com" target="_blank" class="text-gray-500 hover:text-red-600 dark:hover:text-red-400 transition duration-300">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.376.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.376-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                            </svg>
                        </a>
                        <a href="https://twitter.com" target="_blank" class="text-gray-500 hover:text-blue-400 dark:hover:text-blue-300 transition duration-300">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M22.23 5.924c-.807.358-1.67.6-2.577.71a4.52 4.52 0 0 0 1.984-2.496 9.036 9.036 0 0 1-2.866 1.095 4.507 4.507 0 0 0-7.677 4.11 12.8 12.8 0 0 1-9.29-4.71 4.507 4.507 0 0 0 1.395 6.014 4.48 4.48 0 0 1-2.042-.564v.057a4.507 4.507 0 0 0 3.616 4.415 4.52 4.52 0 0 1-2.034.077 4.507 4.507 0 0 0 4.21 3.13 9.04 9.04 0 0 1-5.6 1.93c-.364 0-.724-.021-1.08-.063a12.78 12.78 0 0 0 6.92 2.03c8.3 0 12.84-6.876 12.84-12.84 0-.195-.004-.39-.013-.583a9.172 9.172 0 0 0 2.253-2.34z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </nav>
        <?php
    }



    public function footer($file_name = '') {
        ?>
        <footer class="bg-white dark:bg-gray-900">
            <div class="mx-auto w-full max-w-screen-xl px-4 py-8 lg:py-12"> 
                <div class="md:flex md:justify-between">
                    <div class="mb-8 md:mb-0"> 
                        <a href="<?= ROOT ?>" class="flex items-center">
                            <img src="<?= ROOT ?>public/assets/images/logo.png" class="h-10 me-4" alt="Association El Mountada Logo" /> <!-- Taille du logo augmentée -->
                            <span class="self-center text-2xl font-semibold whitespace-nowrap text-primary">Association El Mountada</span>
                        </a>
                    </div>
                    <div class="grid grid-cols-2 gap-12 sm:gap-8 sm:grid-cols-3"> 
                        <div>
                            <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Ressources</h2>
                            <ul class="text-gray-500 dark:text-gray-400 font-medium">
                                <li class="mb-4">
                                    <a href="<?= ROOT ?>news" class="hover:underline">News</a>
                                </li>
                                <li class="mb-4">
                                    <a href="<?= ROOT ?>partners" class="hover:underline">Remises et avantages</a>
                                </li>
                                <li class="mb-4">
                                    <a href="<?= ROOT ?>partners" class="hover:underline">Catalogue des Partenaires</a>
                                </li>
                                <li>
                                    <a href="<?= ROOT ?>donate" class="hover:underline">Faire un don</a>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Nous suivre</h2>
                            <ul class="text-gray-500 dark:text-gray-400 font-medium">
                                <li class="mb-4">
                                    <a href="https://facebook.com" target="_blank" class="hover:underline">Facebook</a>
                                </li>
                                <li class="mb-4">
                                    <a href="https://youtube.com" target="_blank" class="hover:underline">YouTube</a>
                                </li>
                                <li>
                                    <a href="https://twitter.com" target="_blank" class="hover:underline">Twitter</a>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Légal</h2>
                            <ul class="text-gray-500 dark:text-gray-400 font-medium">
                                <li class="mb-4">
                                    <a href="<?= ROOT ?>privacy" class="hover:underline">Politique de confidentialité</a>
                                </li>
                                <li>
                                    <a href="<?= ROOT ?>terms" class="hover:underline">Conditions d'utilisation</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <hr class="my-8 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-12" /> <!-- Ajustement de la marge -->
                <div class="sm:flex sm:items-center sm:justify-between">
                    <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">
                        © <?= date("Y") ?> <a href="<?= ROOT ?>" class="hover:underline">Association El Mountada</a>. Tous droits réservés.
                    </span>
                    <div class="flex mt-6 sm:mt-0 sm:justify-center space-x-6"> <!-- Ajustement de l'espacement entre les icônes -->
                        <a href="https://facebook.com" target="_blank" class="text-gray-500 hover:text-gray-900 dark:hover:text-white">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" />
                            </svg>
                            <span class="sr-only">Facebook</span>
                        </a>
                        <a href="https://youtube.com" target="_blank" class="text-gray-500 hover:text-gray-900 dark:hover:text-white">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.376.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.376-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                            </svg>
                            <span class="sr-only">YouTube</span>
                        </a>
                        <a href="https://twitter.com" target="_blank" class="text-gray-500 hover:text-gray-900 dark:hover:text-white">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M22.23 5.924c-.807.358-1.67.6-2.577.71a4.52 4.52 0 0 0 1.984-2.496 9.036 9.036 0 0 1-2.866 1.095 4.507 4.507 0 0 0-7.677 4.11 12.8 12.8 0 0 1-9.29-4.71 4.507 4.507 0 0 0 1.395 6.014 4.48 4.48 0 0 1-2.042-.564v.057a4.507 4.507 0 0 0 3.616 4.415 4.52 4.52 0 0 1-2.034.077 4.507 4.507 0 0 0 4.21 3.13 9.04 9.04 0 0 1-5.6 1.93c-.364 0-.724-.021-1.08-.063a12.78 12.78 0 0 0 6.92 2.03c8.3 0 12.84-6.876 12.84-12.84 0-.195-.004-.39-.013-.583a9.172 9.172 0 0 0 2.253-2.34z" />
                            </svg>
                            <span class="sr-only">Twitter</span>
                        </a>
                    </div>
                </div>
            </div>
        </footer>
        <?php if (!empty($file_name)): ?>
            <script src="<?= ROOT ?>public/assets/js/<?= $file_name ?>"></script>
        <?php endif; ?>        
        </body>
        </html>
        <?php
    }

    public function simple_footer($file_name = '') {
        ?>
        <script src="<?= ROOT ?>public/assets/js/<?= $file_name ?>"></script>
        </body>
        </html>
        <?php
    }


}