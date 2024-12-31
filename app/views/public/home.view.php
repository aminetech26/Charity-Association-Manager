<?php
class Home_view
{
    use View;

    public function page_head($page_title) {
        ?>
        <!DOCTYPE html>
        <html lang="fr" class="h-full bg-white">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta http-equiv="cache-control" content="no-cache" />
            <meta http-equiv="Pragma" content="no-cache" />
            <meta http-equiv="Expires" content="-1" />
            <title><?=$page_title?></title>
            <link rel="stylesheet" href="<?= ROOT . "public/assets/css/main.css?v=" . time() ?>">        <body class="h-full bg-white">
        <?php
    }

    public function page_footer() {
        ?>
        </body>
        </html>
        <?php
    }

    public function home() {
        ?>
        <div class="container mx-auto">
            <div class="flex justify-center items-center h-screen">
                <h1 class="text-4xl font-bold text-center">Welcome to Association El Mountada</h1>
            </div>
        </div>
        <?php
    }

    public function about() {
        ?>
        <div class="container mx-auto">
            <div class="flex justify-center items-center h-screen">
                <h1 class="text-4xl font-bold text-center">About Association El Mountada</h1>
            </div>
        </div>
        <?php
    }

    public function contact() {
        ?>
        <div class="container mx-auto">
            <div class="flex justify-center items-center h-screen">
                <h1 class="text-4xl font-bold text-center">Contact Association El Mountada</h1>
            </div>
        </div>
        <?php
    }

    public function error() {
        ?>
        <div class="container mx-auto">
            <div class="flex justify-center items-center h-screen">
                <h1 class="text-4xl font-bold text-center">404 Not Found</h1>
            </div>
        </div>
        <?php
    }

    public function login() {
        ?>
        <div class="container mx-auto">
            <div class="flex justify-center items-center h-screen">
                <h1 class="text-4xl font-bold text-center">Login</h1>
            </div>
        </div>
        <?php
    }

    public function register() {
        ?>
        <div class="container mx-auto">
            <div class="flex justify-center items-center h-screen">
                <h1 class="text-4xl font-bold text-center">Register</h1>
            </div>
        </div>
        <?php
    }

}
