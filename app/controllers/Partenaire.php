<?php
//defined('ROOTPATH') OR exit('Accès refusé !');
class Partenaire{
    use Controller;

    public function signin(){
        $erreurs = [];
        $this->model('ComptePartenaire');
        $this->model('Partenaire');
    
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $comptePartenaire = new ComptePartenaireModel();
            $partenaire = new PartenaireModel();
            if (empty($_POST['email']) || empty($_POST['mot_de_passe'])) {
                $erreurs[] = "L'adresse e-mail et le mot de passe sont requis.";
            } else {
                $partner = $comptePartenaire->first(['email' => $_POST['email']]);
                if ($partner && password_verify($_POST['mot_de_passe'], $partner->mot_de_passe)) {
                    if($partner->statut == 'BLOCKED'){
                        $erreurs[] = "Votre compte a été désactivé. Veuillez contacter l'administrateur.";
                        echo json_encode(['status' => 'error', 'message' => $erreurs ? implode(', ', $erreurs) : 'Erreur inconnue.']);
                        exit();
                    }
                    $partnerId = $partner->partenaire_id;
                    $partenaireInfo = $partenaire->getPartenaireById($partnerId);
                    $_SESSION['partenaire_id'] = $partenaireInfo[0]->id;
                    $_SESSION['partenaire_nom'] = $partenaireInfo[0]->nom;
                    $_SESSION['partenaire_ville'] = $partenaireInfo[0]->ville;

                    echo json_encode(['status' => 'success', 'message' => 'Connexion réussie !']);
                    exit();
                } else {
                    $erreurs[] = "Adresse e-mail ou mot de passe incorrect.";
                }
            }
        }
    
        echo json_encode(['status' => 'error', 'message' => $erreurs ? implode(', ', $erreurs) : 'Erreur inconnue.']);
        exit();

    }

    public function checkIfLoggedInAsPartner(){
        if (!isset($_SESSION['partenaire_id'])) {
            redirect('public/Home/signin');
        }
    }

    public function logout(){
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        echo json_encode(['status' => 'success', 'message' => 'Déconnexion réussie !']);
        redirect('public/Home/signin');
        exit();
    }

public function checkIfMemberEligible() {
    $this->checkIfLoggedInAsPartner();
    $this->model('Membre');
    $this->model('Partenaire');
    $this->model('Offre');
    $this->model('Abonnement');

    
    if ($_SERVER['REQUEST_METHOD'] !== "POST") {
        echo json_encode([
            'status' => 'error',
            'message' => 'Method not allowed'
        ]);
        exit();
    }

    $membre = new MembreModel();
    $partenaire = new PartenaireModel();
    $offre = new OffreModel();
    $abonnement = new AbonnementModel();

    // Validate input
    if (empty($_POST['member_unique_id'])) {
        echo json_encode([
            'status' => 'error',
            'message' => "L'identifiant unique du membre est requis."
        ]);
        exit();
    }

    $member = $membre->getMemberByUniqueId($_POST['member_unique_id']);
    if (!$member) {
        echo json_encode([
            'status' => 'error',
            'message' => "Membre introuvable."
        ]);
        exit();
    }

    $memberSubscription = $member[0]->abonnement_id;
    if ($memberSubscription === NULL) {
        echo json_encode([
            'qrCode' => $member[0]->qr_code,
            'status' => 'error',
            'message' => "Ce membre n'est pas abonné.",
            'isSubscribed' => false
        ]);
        exit();
    }

    $abonnementType = $abonnement->getSubscriptionById($memberSubscription);
    $typeSubscription = $abonnementType[0]->type_abonnement;
    $partner = $partenaire->getPartenaireById($_SESSION['partenaire_id']);
    $offerMember = $offre->getOffersByTypeAndPartnerId($typeSubscription, $partner[0]->id);

    if (empty($offerMember)) {
        echo json_encode([
            'status' => 'error',
            'qrCode' => $member[0]->qr_code,
            'message' => "Vous ne proposez aucun offre dans la catégorie $typeSubscription.",
            'isSubscribed' => true,
            'noOffers' => true,
            'memberType' => $typeSubscription
        ]);
        exit();
    }

    echo json_encode([
        'status' => 'success',
        'qrCode' => $member[0]->qr_code,
        'message' => 'Membre éligible pour l\'offre suivant : ' . $offerMember[0]->description,
        'isSubscribed' => true,
        'offer' => $offerMember[0],
        'memberType' => $typeSubscription
    ]);
    exit();
}


    public function addRemise(){
        $this->checkIfLoggedInAsPartner();
        $erreurs = [];
        $this->model('Membre');
        $this->model('offre');
        $this->model('RemiseObtenus');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $membre = new MembreModel();
            $offre = new OffreModel();
            $remiseObtenus = new RemiseObtenusModel();
            if (empty($_POST['member_unique_id']) || empty($_POST['offre_id'] || empty($_POST['date_benefice']))) {
                $erreurs[] = "L'identifiant du membre et de l'offre et la date de benefice sont requis.";
                echo json_encode(['status' => 'error', 'message' => $erreurs ? implode(', ', $erreurs) : 'Erreur inconnue.']);
                exit();
            } else {
                $member = $membre->getMemberByUniqueId($_POST['member_unique_id']);
                if (!$member) {
                    $erreurs[] = "Membre introuvable.";
                    echo json_encode(['status' => 'error', 'message' => $erreurs ? implode(', ', $erreurs) : 'Erreur inconnue.']);
                    exit();
                } else {
                    $offer = $offre->getOfferById($_POST['offre_id']);
                    if (!$offer) {
                        $erreurs[] = "Offre introuvable.";
                        echo json_encode(['status' => 'error', 'message' => $erreurs ? implode(', ', $erreurs) : 'Erreur inconnue.']);
                        exit();
                    } else {
                        if($offer[0]->partenaire_id != $_SESSION['partenaire_id']){
                            $erreurs[] = "Vous n'êtes pas autorisé à ajouter une remise pour cette offre.";
                            echo json_encode(['status' => 'error', 'message' => $erreurs ? implode(', ', $erreurs) : 'Erreur inconnue.']);
                            exit();
                        }
                        if($offer[0]->date_fin < date('Y-m-d')){
                            $erreurs[] = "L'offre n'est plus valide.";
                            echo json_encode(['status' => 'error', 'message' => $erreurs ? implode(', ', $erreurs) : 'Erreur inconnue.']);
                            exit();
                        }
                        $donnees = [
                            'compte_membre_id' => $member[0]->id,
                            'offre_id' => $_POST['offre_id'],
                            'date_benefice' => $_POST['date_benefice']
                        ];	
                        $remiseObtenus->insert($donnees);
                        echo json_encode(['status' => 'success', 'message' => 'Remise ajoutée avec succès.']);
                        exit();
                    }
                }
            }
        }
    }

    public function getAllRemises(){
        $this->checkIfLoggedInAsPartner();
        $this->model('RemiseObtenus');
        $remiseObtenus = new RemiseObtenusModel();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $offset = ($page - 1) * $limit;

        $remises = $remiseObtenus->getRemiseParPartenaire($_SESSION['partenaire_id'], $limit, $offset);   

        $total = $remiseObtenus->getTotalRemisesParPartenaire($_SESSION['partenaire_id']);
        if(!$remises){
            $remises = [];
        }
        
        echo json_encode([
            'status' => 'success',
            'data' => $remises,
            'pagination' => [
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'total_pages' => ceil($total / $limit)
            ]
        ]);
        exit();
    }


    public function getPartnerInfo(){
        $this->checkIfLoggedInAsPartner();
        $this->model('Partenaire');
        $partner = new PartenaireModel();
        $partnerInfo = $partner->getPartnerInfosWithCategory($_SESSION['partenaire_id']);    
            if(empty($partnerInfo)){
                $partnerInfo = [];
            }
            echo json_encode([
                'status' => 'success',
                'data' => $partnerInfo,
            ]);
            exit();
    }

    public function getPartnerOffers(){
        $this->checkIfLoggedInAsPartner();
        $this->model('offre');
        $offer = new OffreModel();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $offset = ($page - 1) * $limit;

        $partnerOffers = $offer->getOffersByPartnerId($_SESSION['partenaire_id']);    

        $total = $partnerOffers ? count($partnerOffers) : 0;
        if(!$partnerOffers){
            $partnerOffers = [];
        }
        
        echo json_encode([
            'status' => 'success',
            'data' => $partnerOffers,
            'pagination' => [
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'total_pages' => ceil($total / $limit)
            ]
        ]);
        exit();
    }

    public function dashboard(){
        $this->checkIfLoggedInAsPartner();
        $this->view("partenaire_dashboard","partenaire");
        $view = new Partenaire_dashboard_view();
        $view->page_head('Partenaire Dashboard');
		$view->show_dashboard_page();
        $view->simple_footer();
    }

    public function info_content(){
        $this->checkIfLoggedInAsPartner();
        $content = $this->view("info","partenaire", true);
        echo $content;
    }

    public function verification_content(){
        $this->checkIfLoggedInAsPartner();
        $content = $this->view("verification","partenaire", true);
        echo $content;
    }

    public function remise_content(){
        $this->checkIfLoggedInAsPartner();
        $content = $this->view("remise","partenaire", true);
        echo $content;
    }
    
    }