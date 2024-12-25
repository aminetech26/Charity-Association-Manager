<?php
class Admin_login_view
{
    use AdminView;
    public function show_login_page()
    { ?>
        <div class="title">Admin Login Page</div>
        <form id="form" method="POST" action="<?= ROOT ?>public/Admin/signIn">
            <div class="forme">
                <label for="email"> Email</label>
                <input type="text" name="email" id="email" required><br>
                <label for="mot_de_passe"> Password</label>
                <input type="password" name="mot_de_passe" id="mot_de_passe" required><br>
                <button type="submit" id="send"> Sign in</button>
            </div>
        </form>
<?php
    }
}