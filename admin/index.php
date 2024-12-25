<?php
session_start();
require "../app/core/init_admin.php";

$admin_app = new App_admin();
$admin_app->loadController();