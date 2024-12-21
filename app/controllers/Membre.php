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
                    ];

                    if (isset($_FILES['recu_paiement']) && $_FILES['recu_paiement']['error'] === 0) {
                        $recuPath = handleFileUpload($_FILES['recu_paiement'], 'recus/');

                        $donneesAbonnement = [
                            'type_abonnement' => 'CLASSIQUE', // mis à jour par l'admin
                            'date_debut' => date('Y-m-d'), // mis à jour par l'admin
                            'date_fin' => date('Y-m-d', strtotime('+1 year')), // mis à jour par l'admin
                            'is_active' => false,
                            'recu_paiement' => $recuPath,
                            'statut' => 'EN_COURS'
                        ];

                        $abonnementId = $abonnement->insert($donneesAbonnement);
                        $donneesMembre['abonnement_id'] = $abonnementId;
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
}
