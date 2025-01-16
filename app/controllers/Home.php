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

    public function fetchHomeNews()
    {
        $this->model("News");
        $newsModel = new NewsModel();
        $news = $newsModel->getAllNewsArticles(5, 0);
        
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'data' => $news
        ]);
    }

    public function fetchMemberBenefits()
    {
        $this->model("Offre");
        $offreModel = new OffreModel();
        $offers = $offreModel->getAllOffers(10, 0); 
        
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'data' => $offers
        ]);
    }

    public function fetchPartnerLogos()
    {
        $this->model("Partenaire");
        $partenaireModel = new PartenaireModel();
        $partners = $partenaireModel->where(['statut' => 'ACTIF']);
        
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'data' => $partners
        ]);
    }

    public function fetchCarouselData()
    {
        $this->model("News");
        $this->model("Offre");
        
        $newsModel = new NewsModel();
        $offreModel = new OffreModel();
        
        $news = $newsModel->getAllNewsArticles(10, 0);
        $offers = $offreModel->getSpecialOffers();
        
        $slides = [];
        
        foreach ($news as $item) {
            $slides[] = [
                'src' => $item->thumbnail_url ?? '',
                'alt' => $item->titre,
                'title' => $item->titre,
                'link' => ROOT . 'public/Home/article/' . $item->id,
                'type' => 'news'
            ];
        }
        
        // Add offers slides
        foreach ($offers as $item) {
            $slides[] = [
                'src' => $item->thumbnail_path ?? ROOT . 'public/assets/images/default-offer.jpg',
                'alt' => $item->type_offre,
                'title' => $item->description,
                'type' => 'offer'
            ];
        }
        
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'data' => $slides
        ]);
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

    public function donate()
    {
        $this->view("donate", "public");
        $view = new Donate_view();
        $view->page_head('Faire un Don');
        
        if(isset($_SESSION['membre_id']) && isset($_SESSION['membre_photo'])){
            $imageUrl = $_SESSION['membre_photo'];
            $trimmedPath = (strpos($imageUrl, "public/") !== false)
                ? explode("public/", $imageUrl)[1]
                : $imageUrl;
            $view->nav_bar(true, $trimmedPath);
        } else {
            $view->nav_bar();
        }
        
        $view->showDonationForm();
        $view->footer("public_donations.js");
    }

    public function processDonation()
    {
        $erreurs = [];
        $this->model('DonS');
        
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $donModel = new DonSModel();
            
            if (!isset($_POST['montant']) || empty($_POST['montant'])) {
                $erreurs[] = "Le montant est requis";
            } elseif (!is_numeric($_POST['montant']) || $_POST['montant'] <= 0) {
                $erreurs[] = "Le montant doit être un nombre positif";
            }
            
            if (!isset($_FILES['recu_paiement']) || $_FILES['recu_paiement']['error'] !== 0) {
                $erreurs[] = "Le reçu de paiement est obligatoire";
            }
            
            if (empty($erreurs)) {
                $donModel->beginTransaction();
                
                try {
                    $recuPath = handleFileUpload($_FILES['recu_paiement'], 'recus_dons/');
                    
                    $donneesDon = [
                        'montant' => $_POST['montant'],
                        'date' => date('Y-m-d H:i:s'),
                        'est_tracable' => 0,
                        'statut' => 'EN_ATTENTE',
                        'recu_paiement' => $recuPath,
                        'membre_id' => null
                    ];
                    
                    $don = $donModel->insert($donneesDon);
                    $donId = $donModel->first(['recu_paiement' => $recuPath])->id;
                    
                    if ($donId) {
                        $donModel->commit();
                        echo json_encode([
                            'status' => 'success',
                            'message' => 'Don enregistré avec succès !',
                            'don_id' => $donId
                        ]);
                        exit();
                    } else {
                        throw new Exception("Erreur lors de l'enregistrement du don");
                    }
                    
                } catch (Exception $e) {
                    $donModel->rollback();
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Une erreur s\'est produite : ' . $e->getMessage()
                    ]);
                    exit();
                }
            }
        }
        
        echo json_encode([
            'status' => 'error',
            'message' => $erreurs ? implode(', ', $erreurs) : 'Erreur inconnue.'
        ]);
        exit();
    }
	
}