<?php
//defined('ROOTPATH') OR exit('Accès refusé !');
require_once(__DIR__ . '/../core/qr_code_helper.php');
class Membre {
    use Controller;

    public function signup() {
        $erreurs = [];
        $this->model('Membre');
        $this->model('Abonnement');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $membre = new MembreModel();
            $abonnement = new AbonnementModel();
            
            $erreurs = $this->validateSignup($_POST, $_FILES);

            if (empty($erreurs)) {
                $membre->beginTransaction();

                try {
                    $photoPath = handleFileUpload($_FILES['photo'], 'photos/');
                    $idPath = handleFileUpload($_FILES['piece_identite'], 'identites/');
                    
                    $donneesMembre = [
                        'nom' => $_POST['nom'],
                        'prenom' => $_POST['prenom'],
                        'email' => $_POST['email'],
                        'mot_de_passe' => password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT),
                        'photo' => $photoPath,
                        'piece_identite' => $idPath,
                        'adresse' => $_POST['adresse'],
                        'numero_de_telephone' => $_POST['numero_de_telephone'],
                        'abonnement_id' => null,
                        'is_approved' => 0
                    ];

                    if (isset($_FILES['recu_paiement']) && $_FILES['recu_paiement']['error'] === 0) {
                        $recuPath = handleFileUpload($_FILES['recu_paiement'], 'recus/');

                        $donneesAbonnement = [
                            'type_abonnement' => 'CLASSIQUE',
                            'date_debut' => date('Y-m-d'),
                            'date_fin' => date('Y-m-d', strtotime('+1 year')),
                            'recu_paiement' => $recuPath,
                            'statut' => 'EN_COURS',
                            'is_active' => 0
                        ];

                        $abonnementId = $abonnement->insert($donneesAbonnement);
                        $donneesMembre['abonnement_id'] = $abonnement->first(['recu_paiement' => $recuPath])->id;
                    }

                    $member = $membre->insert($donneesMembre);
                    $membreId = $membre->first(['email' => $_POST['email']])->id;                    
                    $memberUniqueId = 'MEM-' . date('Y') . '-' . str_pad($membreId, 5, '0', STR_PAD_LEFT);
                    
                    $membre->update($membreId, [
                        'member_unique_id' => $memberUniqueId,
                    ]);

                    $membre->commit();

                    echo json_encode(['status' => 'success', 'message' => 'Inscription réussie !']);
                    exit();
                } catch (Exception $e) {
                    $membre->rollback();
                    $erreurs[] = "Une erreur s'est produite pendant l'inscription. Veuillez réessayer.";
                    echo json_encode(['status' => 'error', 'message' => 'Une erreur s\'est produite : ' . $e->getMessage()]);
                    exit();
                }
            }
        }

        echo json_encode(['status' => 'error', 'message' => $erreurs ? implode(', ', $erreurs) : 'Erreur inconnue.']);
        exit();
    }

    private function validateSignup($post, $files) {
        $erreurs = [];

        $champsObligatoires = ['nom', 'prenom', 'email', 'mot_de_passe', 'adresse', 'numero_de_telephone'];
        foreach ($champsObligatoires as $champ) {
            if (empty($post[$champ])) {
                $erreurs[] = ucfirst($champ) . " est requis.";
            }
        }

        if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
            $erreurs[] = "Adresse e-mail invalide.";
        }

        $membre = new MembreModel();
        if ($membre->first(['email' => $post['email']])) {
            $erreurs[] = "Cette adresse e-mail est déjà utilisée.";
        }

        if (strlen($post['mot_de_passe']) < 6) {
            $erreurs[] = "Le mot de passe doit contenir au moins 6 caractères.";
        }

        if (!isset($files['photo']) || $files['photo']['error'] !== 0) {
            $erreurs[] = "La photo est requise.";
        }

        if (!isset($files['piece_identite']) || $files['piece_identite']['error'] !== 0) {
            $erreurs[] = "La pièce d'identité est requise.";
        }

        return $erreurs;
    }

    public function signIn() {
        $erreurs = [];
        $this->model('Membre');
        $this->model('Abonnement');
    
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $membre = new MembreModel();
            $abonnement = new AbonnementModel();
    
            if (empty($_POST['email']) || empty($_POST['mot_de_passe'])) {
                $erreurs[] = "L'adresse e-mail et le mot de passe sont requis.";
            } else {
                $utilisateur = $membre->first(['email' => $_POST['email']]);
                if ($utilisateur && password_verify($_POST['mot_de_passe'], $utilisateur->mot_de_passe)) {
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }

                    if($utilisateur->is_approved == 0) {
                        echo json_encode(['status' => 'error', 'message' => 'Votre compte n\'a pas encore été approuvé.']);
                        exit();
                    }

                    $_SESSION['membre_id'] = $utilisateur->id;
                    $_SESSION['membre_nom'] = $utilisateur->nom;
                    $_SESSION['membre_prenom'] = $utilisateur->prenom;
                    $_SESSION['membre_email'] = $utilisateur->email;
                    $_SESSION['membre_photo'] = $utilisateur->photo;

                    if (!empty($utilisateur->abonnement_id)) {
                        $detailsAbonnement = $abonnement->first(['id' => $utilisateur->abonnement_id]);
                        if ($detailsAbonnement) {
                            $_SESSION['type_abonnement'] = $detailsAbonnement->type_abonnement;
                            $_SESSION['statut_abonnement'] = $detailsAbonnement->statut;
                            $_SESSION['is_active'] = $detailsAbonnement->is_active;
                        }
                    }
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

    public function signOut() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        echo json_encode(['status' => 'success', 'message' => 'Déconnexion réussie !']);
        redirect('public/Home/index');
        exit();
    }

    public function checkIfLoggedIn() {
        if (!isset($_SESSION['membre_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Vous devez être connecté pour effectuer cette action.']);
            redirect('public/Home/signin');
            exit();
        }
    }

    // Partenaire favoris

    public function markPartnerAsFavourite(){
        $this->checkIfLoggedIn();
        $this->model('PartenaireFavori');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $favori = new PartenaireFavoriModel();

            $donnees = [
                'compte_membre_id' => $_SESSION['membre_id'],
                'partenaire_id' => $_POST['partenaire_id']
            ];

            if ($favori->first($donnees)) {
                echo json_encode(['status' => 'error', 'message' => 'Ce partenaire est déjà dans vos favoris.']);
                exit();
            }

            $favori->insert($donnees);
            echo json_encode(['status' => 'success', 'message' => 'Partenaire ajouté à vos favoris.']);
            exit();
        }
    }

    public function removePartnerFromFavourites(){
        $this->checkIfLoggedIn();
        $this->model('PartenaireFavori');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $favori = new PartenaireFavoriModel();

            $donnees = [
                'compte_membre_id' => $_SESSION['membre_id'],
                'partenaire_id' => $_POST['partenaire_id']
            ];

            $favoriId = $favori->first($donnees)->id;
            if (!$favoriId) {
                echo json_encode(['status' => 'error', 'message' => 'Partenaire non trouvé dans vos favoris.']);
                exit();
            }
            $favori->delete($favoriId);
            echo json_encode(['status' => 'success', 'message' => 'Partenaire retiré de vos favoris.']);
            exit();
        }
    }

    public function getFavouritePartners(){
        $this->checkIfLoggedIn();
        $this->model('PartenaireFavori');
        $this->model('Partenaire');

        $favori = new PartenaireFavoriModel();
        $partenaire = new PartenaireModel();

        $favoris = $favori->where(['compte_membre_id' => $_SESSION['membre_id']]) ?? [];
        $partenaires = [];

        foreach ($favoris as $favori) {
            $partenaire = $partenaire->first(['id' => $favori->partenaire_id]);
            $partenaires[] = $partenaire;
        }

        echo json_encode(['status' => 'success', 'data' => $partenaires]);
        exit();
    }

    // Dons made by a member

    public function ajouterDon() {
        $erreurs = [];
        $this->model('Dons');
        
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $donModel = new DonsModel();
            
            $erreurs = $this->validateDon($_POST, $_FILES);
            
            if (isset($_POST['est_tracable']) && $_POST['est_tracable'] == true && !isset($_SESSION['membre_id'])) {
                $erreurs[] = "Vous devez être connecté pour faire un don traçable";
                echo json_encode(['status' => 'error', 'message' => implode(', ', $erreurs)]);
                exit();
            }
            
            if (empty($erreurs)) {
                $donModel->beginTransaction();
                
                try {
                    $recuPath = handleFileUpload($_FILES['recu_paiement'], 'recus_dons/');
                    
                    $donneesDon = [
                        'montant' => $_POST['montant'],
                        'date' => date('Y-m-d H:i:s'),
                        'est_tracable' => isset($_POST['est_tracable']) ? $_POST['est_tracable'] : 0,
                        'statut' => 'EN_ATTENTE',
                        'recu_paiement' => $recuPath
                    ];
                    
                    if ($donneesDon['est_tracable']) {
                        $donneesDon['compte_membre_id'] = $_SESSION['membre_id'];
                    }
                    
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
    
    private function validateDon($data, $files) {
        $erreurs = [];

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['date'])) {
            $erreurs[] = "Date de don invalide. Format attendu : YYYY-MM-DD.";
            return $erreurs;
        }
        
        if (!isset($data['montant']) || empty($data['montant'])) {
            $erreurs[] = "Le montant est requis";
        } elseif (!is_numeric($data['montant']) || $data['montant'] <= 0) {
            $erreurs[] = "Le montant doit être un nombre positif";
        }
        
        if (!isset($files['recu_paiement']) || $files['recu_paiement']['error'] !== 0) {
            $erreurs[] = "Le reçu de paiement est obligatoire";
        }
        
        if (isset($data['est_tracable'])) {
            if (!is_bool($data['est_tracable']) && $data['est_tracable'] !== "0" && $data['est_tracable'] !== "1") {
                $erreurs[] = "La valeur de est_tracable doit être un booléen";
            }
        }
        
        return $erreurs;
    }

    public function getMemberDonations() {
        $this->checkIfLoggedIn();
        $this->model('Dons');
        
        $donModel = new DonsModel();
        $dons = $donModel->where(['compte_membre_id' => $_SESSION['membre_id']]) ?? [];
        
        echo json_encode(['status' => 'success', 'data' => $dons]);
        exit();
    }

    // Bénévoler dans un événement

    public function volunteerForEvent() {
        $this->checkIfLoggedIn();
        $this->model('benevolats');
        $this->model('evenement');
        
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $benevoleModel = new BenevolatsModel();
            $evenementModel = new EvenementModel();
            $erreurs = [];
            
            // Validate event exists
            if (!isset($_POST['evenement_id']) || !$evenementModel->first(['id' => $_POST['evenement_id']])) {
                echo json_encode(['status' => 'error', 'message' => 'Événement invalide']);
                exit();
            }
            
            $evenement = $evenementModel->first(['id' => $_POST['evenement_id']]);
            
            $donnees = [
                'compte_membre_id' => $_SESSION['membre_id'],
                'evenement_id' => $_POST['evenement_id'],
                'statut' => 'EN_ATTENTE'
            ];
            
            if ($benevoleModel->first(['compte_membre_id' => $_SESSION['membre_id'], 'evenement_id' => $_POST['evenement_id']])) {
                echo json_encode(['status' => 'error', 'message' => 'Vous êtes déjà bénévole pour cet événement.']);
                exit();
            }
            
            $benevoleModel->insert($donnees);
            echo json_encode(['status' => 'success', 'message' => 'Vous êtes désormais bénévole pour cet événement.']);
            exit();
        }
    }


    // Renouveller l'abonnement

    public function creerAbonnement(){
        $this->checkIfLoggedIn();
        $this->model('Abonnement');
        $this->model('Membre');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $abonnementModel = new AbonnementModel();
            $membre = new MembreModel();
            
            $abonnementMembre = $membre->first(['id' => $_SESSION['membre_id']])->abonnement_id;

            if (!isset($files['recu_paiement']) || $files['recu_paiement']['error'] !== 0) {
                $erreurs[] = "Le reçu de paiement est obligatoire";
            }

            if($abonnementMembre){
                echo json_encode(['status' => 'error', 'message' => 'Vous avez déjà un abonnement en cours. Vous devez demander un renouvellement.']);
                exit();
            }

            $donnees = [
                'type_abonnement' => $_POST['type_abonnement'],
                'date_debut' => date('Y-m-d'),
                'date_fin' => date('Y-m-d', strtotime('+1 year')),
                'recu_paiement' => null,
                'statut' => 'EN_COURS',
                'is_active' => 0
            ];

            if (isset($_FILES['recu_paiement']) && $_FILES['recu_paiement']['error'] === 0) {
                $recuPath = handleFileUpload($_FILES['recu_paiement'], 'recus/');
                $donnees['recu_paiement'] = $recuPath;
            }

            try {
                $abonnement = $abonnementModel->insert($donnees);
                $abonnementId = $abonnementModel->first(['recu_paiement' => $donnees['recu_paiement']])->id;
                $membre->update($_SESSION['membre_id'], ['abonnement_id' => $abonnementId]);
                echo json_encode(['status' => 'success', 'message' => 'Abonnement créé avec succès !']);
                exit();
            } catch (Exception $e) {
                echo json_encode(['status' => 'error', 'message' => 'Une erreur s\'est produite : ' . $e->getMessage()]);
                exit();
            }
        }
    }

    public function renouvelerAbonnement() {
        $this->checkIfLoggedIn();
        $this->model('Abonnement');
        $this->model('Membre');
    
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $abonnementModel = new AbonnementModel();
            $membre = new MembreModel();
    
            $membreData = $membre->first(['id' => $_SESSION['membre_id']]);
            $abonnementMembre = $membreData->abonnement_id;
    
            if (!$abonnementMembre) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Vous n\'avez pas d\'abonnement en cours. Vous devez en créer un nouveau.'
                ]);
                exit();
            }
    
            $currentAbonnement = $abonnementModel->first(['id' => $abonnementMembre]);
            if (!$currentAbonnement) {
                echo json_encode(['status' => 'error', 'message' => 'L\'abonnement actuel n\'existe pas.']);
                exit();
            }
    
            if (date('Y-m-d') < $currentAbonnement->date_fin) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Votre abonnement est encore valide.'
                ]);
                exit();
            }
    
            // Validate payment receipt
            if (!isset($_FILES['recu_paiement']) || $_FILES['recu_paiement']['error'] !== 0) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Le reçu de paiement est obligatoire.'
                ]);
                exit();
            }
    
            // Prepare new abonnement data
            $donnees = [
                'type_abonnement' => $_POST['type_abonnement'],
                'date_debut' => date('Y-m-d'),
                'date_fin' => date('Y-m-d', strtotime('+1 year')),
                'recu_paiement' => null,
                'statut' => 'EN_COURS',
                'is_active' => 0
            ];
    
            if (isset($_FILES['recu_paiement']) && $_FILES['recu_paiement']['error'] === 0) {
                $recuPath = handleFileUpload($_FILES['recu_paiement'], 'recus/');
                $donnees['recu_paiement'] = $recuPath;
            }
    
    
            try {
                $abonnement = $abonnementModel->insert($donnees);
                $abonnementId = $abonnementModel->first(['recu_paiement' => $donnees['recu_paiement']])->id;
    
                $membre->update($_SESSION['membre_id'], ['abonnement_id' => $abonnementId]);
    
    
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Abonnement renouvelé avec succès !'
                ]);
                exit();
            } catch (Exception $e) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Une erreur s\'est produite : ' . $e->getMessage()
                ]);
                exit();
            }
        }
    }

    public function getMemberInfos() {
        $this->checkIfLoggedIn();
        $this->model('Membre');
    
        $membre = new MembreModel();
        $membreData = $membre->first(['id' => $_SESSION['membre_id']]);
    
        echo json_encode(['status' => 'success', 'data' => $membreData]);
        exit();
    }

    public function updateMemberInfos(){
        $this->checkIfLoggedIn();
        $this->model('Membre');
    
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $membre = new MembreModel();

            if(!empty($_POST['mot_de_passe']) && strlen($_POST['mot_de_passe']) < 6){
                echo json_encode(['status' => 'error', 'message' => 'Le mot de passe doit contenir au moins 6 caractères.']);
                exit();
            }
            if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
                echo json_encode(['status' => 'error', 'message' => 'Adresse e-mail invalide.']);
                exit();
            }
            if($membre->first(['email' => $_POST['email']]) && $membre->first(['email' => $_POST['email']])->id != $_SESSION['membre_id']){
                echo json_encode(['status' => 'error', 'message' => 'Cette adresse e-mail est déjà utilisée.']);
                exit();
            }
            if(!empty($_POST['numero_de_telephone']) && !preg_match('/^\+?[0-9]{3}-?[0-9]{6,12}$/', $_POST['numero_de_telephone'])){
                echo json_encode(['status' => 'error', 'message' => 'Numéro de téléphone invalide.']);
                exit();
            }
            if(!empty($_POST['adresse']) && strlen($_POST['adresse']) < 5){
                echo json_encode(['status' => 'error', 'message' => 'Adresse invalide
                .']);
                exit();
            }
    
            $donnees = [
                'email' => $_POST['email'],
                'mot_de_passe' => password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT),
                'adresse' => $_POST['adresse'],
                'numero_de_telephone' => $_POST['numero_de_telephone']
            ];
    
            $membre->update($_SESSION['membre_id'], $donnees);
    
            echo json_encode(['status' => 'success', 'message' => 'Informations mises à jour avec succès !']);
            exit();
        }
    }


    public function getTypeAides(){
        $this->checkIfLoggedIn();
        $this->model('TypeAide');
    
        $typeAide = new TypeAideModel();
        $types = $typeAide->fetchAll();
    
        echo json_encode(['status' => 'success', 'data' => $types]);
        exit();
    }

    public function ajouterDemandeAide(){
        $this->checkIfLoggedIn();
        $this->model('DemandeAide');
    
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $demandeAide = new DemandeAideModel();

            if(!isset($_POST['nom']) || empty($_POST['nom'])){
                echo json_encode(['status' => 'error', 'message' => 'Le nom est requis.']);
                exit();
            }

            if(!isset($_POST['prenom']) || empty($_POST['prenom'])){
                echo json_encode(['status' => 'error', 'message' => 'Le prénom est requis.']);
                exit();
            }

            if(!isset($_POST['date_naissance']) || empty($_POST['date_naissance'])){
                echo json_encode(['status' => 'error', 'message' => 'La date de naissance est requise.']);
                exit();
            }

            if(!isset($_POST['type_aide']) || empty($_POST['type_aide'])){
                echo json_encode(['status' => 'error', 'message' => 'Le type d\'aide est requis.']);
                exit();
            }

            if(!isset($_POST['description']) || empty($_POST['description'])){
                echo json_encode(['status' => 'error', 'message' => 'La description est requise.']);
                exit();
            }

            if(!isset($_FILES['fichier_zip']) || $_FILES['fichier_zip']['error'] !== 0){
                echo json_encode(['status' => 'error', 'message' => 'Le fichier ZIP est requis.']);
                exit();
            }

            if(isset($_FILES['fichier_zip']) && $_FILES['fichier_zip']['error'] !== 0){
                echo json_encode(['status' => 'error', 'message' => 'Erreur lors du téléchargement du fichier ZIP.']);
                exit();
            }
    
            $donnees = [
                'nom' => $_POST['nom'],
                'prenom' => $_POST['prenom'],
                'date_naissance' => $_POST['date_naissance'],
                'type_aide' => $_POST['type_aide'],
                'description' => $_POST['description'],
                'fichier_zip' => null
            ];
    
            $zipPath = handleFileUpload($_FILES['fichier_zip'], 'dossiers_aide/');
            $donnees['fichier_zip'] = $zipPath;
    
            $demandeAide->insert($donnees);
    
            echo json_encode(['status' => 'success', 'message' => 'Demande d\'aide enregistrée avec succès !']);
            exit();
        }
    }

    public function dashboard(){
        $this->checkIfLoggedIn();
        $this->view("membre_dashboard","membre");
        $view = new Membre_dashboard_view();
        $view->page_head('Membre Dashboard');
		$view->show_dashboard_page();
        $view->simple_footer();
    }

    public function profile_content(){
        $this->checkIfLoggedIn();
        $content = $this->view("profile_content","membre", true);
        echo $content;
    }

    public function subscription_content(){
        $this->checkIfLoggedIn();
        $content = $this->view("subscription_content","membre", true);
        echo $content;
    }

    public function favorites_content(){
        $this->checkIfLoggedIn();
        $content = $this->view("favorites_content","membre", true);
        echo $content;
    }

    public function volunteer_content(){
        $this->checkIfLoggedIn();
        $content = $this->view("volunteer_content","membre", true);
        echo $content;
    }

    public function history_content(){
        $this->checkIfLoggedIn();
        $content = $this->view("history_content","membre", true);
        echo $content;
    }

    public function donate_content(){
        $this->checkIfLoggedIn();
        $content = $this->view("donate_content","membre", true);
        echo $content;
    }

    public function assistance_content(){
        $this->checkIfLoggedIn();
        $content = $this->view("assistance_content","membre", true);
        echo $content;
    }

    public function feedback_content(){
        $this->checkIfLoggedIn();
        $content = $this->view("feedback_content","membre", true);
        echo $content;
    }
}
