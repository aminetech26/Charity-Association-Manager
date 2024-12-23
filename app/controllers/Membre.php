<?php
//defined('ROOTPATH') OR exit('Accès refusé !');
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
                            'type_abonnement' => 'CLASSIQUE', // mis à jour par l'admin
                            'date_debut' => date('Y-m-d'), // mis à jour par l'admin
                            'date_fin' => date('Y-m-d', strtotime('+1 year')), // mis à jour par l'admin
                            'recu_paiement' => $recuPath,
                            'statut' => 'EN_COURS',
                            'is_active' => 0
                        ];

                        $abonnementId = $abonnement->insert($donneesAbonnement);
                        $donneesMembre['abonnement_id'] = $abonnement->first(['recu_paiement' => $recuPath])->id;
                    }
                    // Création du compte membre
                    $membre->insert($donneesMembre);

                    // Validation de la transaction
                    $membre->commit();
                    echo json_encode(['status' => 'success', 'message' => 'Inscription réussie !']);
                    exit();

                } catch (Exception $e) {
                    // Annulation en cas d'erreur
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

        // Validation des champs obligatoires
        $champsObligatoires = ['nom', 'prenom', 'email', 'mot_de_passe', 'adresse', 'numero_de_telephone'];
        foreach ($champsObligatoires as $champ) {
            if (empty($post[$champ])) {
                $erreurs[] = ucfirst($champ) . " est requis.";
            }
        }

        // Validation de l'email
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

                    // If the member has an abonnement_id, retrieve additional details
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
    
        // Return errors if any
        echo json_encode(['status' => 'error', 'message' => $erreurs ? implode(', ', $erreurs) : 'Erreur inconnue.']);
        exit();
    }

    public function signOut() {
        session_unset();
        session_destroy();
        echo json_encode(['status' => 'success', 'message' => 'Déconnexion réussie !']);
        exit();
    }

    public function checkIfLoggedIn() {
        if (!isset($_SESSION['membre_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Vous devez être connecté pour effectuer cette action.']);
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

}
