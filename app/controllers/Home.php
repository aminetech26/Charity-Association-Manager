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
        if(isset($_SESSION['membre_id']) && isset($_SESSION['membre_photo'])){
            $imageUrl = $_SESSION['membre_photo'];
            $trimmedPath = (strpos($imageUrl, "public/") !== false)
                ? explode("public/", $imageUrl)[1]
                : $imageUrl;
            $view->nav_bar(true,$trimmedPath);
        }else{
            $view->nav_bar();
        }
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

    public function news()
    {
        $this->view("news", "public");

        $view = new News_view();
        $view->page_head('News');
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 6;
        $offset = ($page - 1) * $limit;

		$this->model("News");
        $newsModel = new NewsModel();
        $news = $newsModel->getAllNewsArticles($limit, $offset);
        $totalNews = $newsModel->getTotalNewsArticles();
        $totalPages = ceil($totalNews / $limit);

        if(isset($_SESSION['membre_id']) && isset($_SESSION['membre_photo'])){
            $imageUrl = $_SESSION['membre_photo'];
            $trimmedPath = (strpos($imageUrl, "public/") !== false)
                ? explode("public/", $imageUrl)[1]
                : $imageUrl;
            $view->nav_bar(true, $trimmedPath);
        } else {
            $view->nav_bar();
        }

        $view->showNewsList($news, $page, $totalPages);
        $view->footer("news.js");
    }

    public function article($params = [])
    {
		$this->model("News");
        if (empty($params[0])) {
            redirect("public/Home/news");
            return;
        }

        $articleId = $params[0];
        $newsModel = new NewsModel();
        $article = $newsModel->getArticleById($articleId)[0];

        if (!$article) {
            redirect("public/Home/news");
            return;
        }

        $this->view("news", "public");
        $view = new News_view();
        $view->page_head($article->titre);

        if(isset($_SESSION['membre_id']) && isset($_SESSION['membre_photo'])){
            $imageUrl = $_SESSION['membre_photo'];
            $trimmedPath = (strpos($imageUrl, "public/") !== false)
                ? explode("public/", $imageUrl)[1]
                : $imageUrl;
            $view->nav_bar(true, $trimmedPath);
        } else {
            $view->nav_bar();
        }

        $view->showNewsDetails($article);
        $view->footer("news.js");
    }

    public function fetchMoreNews()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 6;
        $offset = ($page - 1) * $limit;
		$this->model("News");
        $newsModel = new NewsModel();
        $articles = $newsModel->getAllNewsArticles($limit, $offset);
        
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'articles' => $articles
        ]);
    }
}