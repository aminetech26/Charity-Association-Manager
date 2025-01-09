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
        if(isset($_SESSION['partenaire_id'])){
            echo json_encode(['status' => 'success', 'message' => 'Vous êtes connecté en tant que partenaire.']);
            exit();
        }else{
            echo json_encode(['status' => 'error', 'message' => 'Vous n\'êtes pas connecté en tant que partenaire.']);
            exit();
        }
    }

    public function logout(){
        session_destroy();
        echo json_encode(['status' => 'success', 'message' => 'Vous avez été déconnecté.']);
        exit();
    }

    public function checkIfMemberEligible(){
        $erreurs = [];
        $this->model('Membre');
        $this->model('Partenaire');
        $this->model('Abonnement');
        $this->model('offre');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $membre = new MembreModel();
            $partenaire = new PartenaireModel();
            $offre = new OffreModel();
            $abonnement = new AbonnementModel();
            if (empty($_POST['membre_id'])) {
                $erreurs[] = "L'identifiant du membre est requis.";
                echo json_encode(['status' => 'error', 'message' => $erreurs ? implode(', ', $erreurs) : 'Erreur inconnue.']);
                exit();
            } else {
                $member = $membre->getMemberById($_POST['membre_id']);
                if (!$member) {
                    $erreurs[] = "Membre introuvable.";
                    echo json_encode(['status' => 'error', 'message' => $erreurs ? implode(', ', $erreurs) : 'Erreur inconnue.']);
                    exit();
                } else {
                    $memberSubscription = $member[0]->abonnement_id;
                    if($memberSubscription == NULL){
                        $erreurs[] = "Ce membre n'est pas abonné.";
                        echo json_encode(['status' => 'error', 'message' => $erreurs ? implode(', ', $erreurs) : 'Erreur inconnue.']);
                        exit();
                    }else{
                        $abonnementType = $abonnement->getSubscriptionById($memberSubscription);
                        $typeSubscription = $abonnementType[0]->type_abonnement;
                        $partner = $partenaire->getPartenaireById($_SESSION['partenaire_id']);
                        $offerMember = $offre->getOffersByTypeAndPartnerId($typeSubscription, $partner[0]->id);
                        if(empty($offerMember)){
                            $erreurs[] = "Vous ne proposez aucun offre dans la catégorie $typeSubscription.";
                            echo json_encode(['status' => 'error', 'message' => $erreurs ? implode(', ', $erreurs) : 'Erreur inconnue.']);
                            exit();
                        }
                        if(!empty($offerMember)){
                            echo json_encode(['status' => 'success', 'message' => 'Membre éligible pour l\'offre suivant : '.$offerMember[0]->description]);
                            exit();
                        }
                    }
                    
                }
            }
            }
        }


    public function addRemiseObtenus(){
        $erreurs = [];
        $this->model('Membre');
        $this->model('offre');
        $this->model('RemiseObtenus');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $membre = new MembreModel();
            $offre = new OffreModel();
            $remiseObtenus = new RemiseObtenusModel();
            if (empty($_POST['membre_id']) || empty($_POST['offre_id'])) {
                $erreurs[] = "L'identifiant du membre et de l'offre sont requis.";
                echo json_encode(['status' => 'error', 'message' => $erreurs ? implode(', ', $erreurs) : 'Erreur inconnue.']);
                exit();
            } else {
                $member = $membre->getMemberById($_POST['membre_id']);
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
                            'compte_membre_id' => $_POST['membre_id'],
                            'offre_id' => $_POST['offre_id'],
                            'date_benefice' => date('Y-m-d')
                        ];	
                        $remiseObtenus->insert($donnees);
                        echo json_encode(['status' => 'success', 'message' => 'Remise ajoutée avec succès.']);
                        exit();
                    }
                }
            }
        }
    }

    public function dashboard(){
        $this->view("partenaire_dashboard","partenaire");
        $view = new Partenaire_dashboard_view();
        $view->page_head('Partenaire Dashboard');
		$view->show_message();
    }
    
    }