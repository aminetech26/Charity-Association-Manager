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
                        'abonnement_id' => null
                    ];

                    if (isset($_FILES['recu_paiement']) && $_FILES['recu_paiement']['error'] === 0) {
                        $recuPath = handleFileUpload($_FILES['recu_paiement'], 'recus/');

                        $donneesAbonnement = [
                            'type_abonnement' => 'CLASSIQUE', // mis à jour par l'admin
                            'date_debut' => date('Y-m-d'), // mis à jour par l'admin
                            'date_fin' => date('Y-m-d', strtotime('+1 year')), // mis à jour par l'admin
                            'recu_paiement' => $recuPath,
                            'statut' => 'EN_COURS'
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

                    $_SESSION['id'] = $utilisateur->id;
                    $_SESSION['nom'] = $utilisateur->nom;
                    $_SESSION['prenom'] = $utilisateur->prenom;
                    $_SESSION['email'] = $utilisateur->email;

                    // If the user has an abonnement_id, retrieve additional details
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
    

}
