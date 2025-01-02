<?php
//defined('ROOTPATH') OR exit('Access Denied!');
class Home
{
	use Controller;

	public function index()
	{
		$this->view("home","public");
        $view = new Home_view();
        $view->page_head('Association El Mountada');
		$view->nav_bar();
		$view->show_diaporama();
		$view->showNewsSection();
		$view->showMemberBenefits();
		$view->showPartnersLogosSection();
        $view->footer();
	}

}