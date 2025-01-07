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
        $view->footer("home.js");
	}


	public function catalogue_partenaire()
	{
		$this->view("catalogue_partenaire","public");
		$view = new CataloguePartenaire_view();
		$view->page_head('Catalogue Partenaire');
		$view->nav_bar();
		$view->showCataloguePartenaires();
		$view->footer("catalogue_partenaire.js");
	}


	public function signup(){
		$this->view("signup","public");
		$view = new Signup_view();
		$view->page_head('Inscription');
		$view->showSignUpForm();
	}

	public function signin(){
		$this->view("signin","public");
		$view = new Signin_view();
		$view->page_head('Connexion');
		$view->showLoginPage();
	}

}