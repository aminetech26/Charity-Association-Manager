<?php 
class Signin_view {
    use View;
    public function showLoginPage() {
?>
        <div class="flex min-h-full flex-col justify-center px-6 py-6 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                <img class="mx-auto h-20 w-auto" src="<?= ROOT ?>admin/assets/images/logo.png" alt="Logo de l'association">
                <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-text-primary">Se connecter</h2>
            </div>

            <div id="error-alert" class="hidden mt-4 sm:mx-auto sm:w-full sm:max-w-sm">
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert"></div>
            </div>

            <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-sm">
                <div class="flex rounded-md shadow-sm justify-center" role="group">
                    <button type="button" id="member-login" class="login-type-btn px-4 py-2 text-sm font-medium rounded-l-lg bg-primary text-white" data-type="member">
                        Membre
                    </button>
                    <button type="button" id="partner-login" class="login-type-btn px-4 py-2 text-sm font-medium rounded-r-lg bg-white text-text-primary outline outline-1 outline-text-secondary" data-type="partner">
                        Partenaire
                    </button>
                </div>
            </div>

            <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
                <form id="login-form" class="space-y-6" method="POST" action="<?= ROOT ?>public/Admin/signIn">
                    <input type="hidden" name="login_type" id="login_type" value="member">
                    <div>
                        <label for="email" class="block text-sm font-medium leading-6 text-text-primary">email</label>
                        <div class="mt-2">
                            <input
                                type="text"
                                name="email"
                                id="email"
                                autocomplete="email"
                                required
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-text-primary outline outline-1 -outline-offset-1 outline-text-secondary placeholder:text-text-secondary focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-primary sm:text-sm sm:leading-6"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="mot_de_passe" class="block text-sm font-medium leading-6 text-text-primary">Mot de passe</label>
                        <div class="mt-2">
                            <input
                                type="password"
                                name="mot_de_passe"
                                id="mot_de_passe"
                                autocomplete="current-password"
                                required
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-text-primary outline outline-1 -outline-offset-1 outline-text-secondary placeholder:text-text-secondary focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-primary sm:text-sm sm:leading-6"
                            >
                        </div>
                    </div>

                    <div>
                        <button
                            type="submit"
                            class="flex w-full justify-center rounded-md bg-primary px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-primary-hover focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                        >
                            Se connecter
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <script src="<?= ROOT ?>public/assets/js/signin.js"></script>
        </body>
        </html>
<?php
    }
}