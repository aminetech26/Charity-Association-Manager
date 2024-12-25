<?php
require_once dirname(__DIR__) . "/core/Admin_view.php";

class Admin_dashboard_view
{
    use AdminView;
    public function show_dashboard_page()
    { ?>
        <div class="title">Admin Home Page - connection successful</div>
        <h1>Welcome to the admin dashboard</h1>
<?php
    }
}