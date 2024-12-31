<?php

trait View {
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
}