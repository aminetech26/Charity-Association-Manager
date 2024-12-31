<?php
class Admin_login_view
{
    use AdminView;
    public function show_login_page() { 
        ?>
        <div class="flex min-h-full flex-col justify-center px-6 py-6 lg:px-8">
  <div class="sm:mx-auto sm:w-full sm:max-w-sm">
    <img class="mx-auto h-20 w-auto" src="<?= ROOT ?>admin/assets/images/logo.png" alt="Logo de l'association">
    <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-text-primary">Connexion Administrateur</h2>
  </div>

  <!-- Error Alert -->
  <div id="error-alert" class="hidden mt-4 sm:mx-auto sm:w-full sm:max-w-sm">
    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert"></div>
  </div>

  <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
    <form id="login-form" class="space-y-6" method="POST" action="<?= ROOT ?>public/Admin/signIn">
      <div>
        <label for="nom_user" class="block text-sm font-medium leading-6 text-text-primary">Nom d'utilisateur</label>
        <div class="mt-2">
          <input
            type="text"
            name="nom_user"
            id="nom_user"
            autocomplete="username"
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
<script src="<?= ROOT ?>admin/assets/js/admin_login.js"></script>
        <?php
        }
}