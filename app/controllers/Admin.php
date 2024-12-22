<?php
class Admin {
    use Controller;

    private function checkIfSuperAdmin() {
        if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_role']) || $_SESSION['admin_role'] !== 'SUPER_ADMIN') {
            echo json_encode(['status' => 'error', 'message' => 'Accès non autorisé']);
            exit();
        }
    }

    public function signIn() {
        $erreurs = [];
        $this->model('Admin');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $admin = new AdminModel();

            if (empty($_POST['email']) || empty($_POST['mot_de_passe'])) {
                $erreurs[] = "L'adresse e-mail et le mot de passe sont requis.";
            } else {
                $utilisateur = $admin->first(['email' => $_POST['email']]);

                if ($utilisateur && password_verify($_POST['mot_de_passe'], $utilisateur->mot_de_passe)) {
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    $_SESSION['admin_id'] = $utilisateur->id;
                    $_SESSION['admin_nom'] = $utilisateur->nom_user;
                    $_SESSION['admin_email'] = $utilisateur->email;
                    $_SESSION['admin_role'] = $utilisateur->role;

                    echo json_encode([
                        'status' => 'success', 
                        'message' => 'Connexion réussie !',
                        'data' => [
                            'nom' => $utilisateur->nom_user,
                            'role' => $utilisateur->role
                        ]
                    ]);
                    exit();
                } else {

                    $erreurs[] = "Adresse e-mail ou mot de passe incorrect.";
                }
            }
        }

        echo json_encode(['status' => 'error', 'message' => $erreurs ? implode(', ', $erreurs) : 'Erreur inconnue.']);
        exit();
    }

    public function createAdminAccount() {
        $this->checkIfSuperAdmin();
        $erreurs = [];
        $this->model('Admin');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $admin = new AdminModel();
            $erreurs = $this->validateAdminData($_POST);

            if (empty($erreurs)) {
                try {
                    $donneesAdmin = [
                        'nom_user' => $_POST['nom_user'],
                        'email' => $_POST['email'],
                        'mot_de_passe' => password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT),
                        'role' => $_POST['role'] ?? 'ADMIN',
                        'created_by' => $_SESSION['admin_id']
                    ];

                    $admin->insert($donneesAdmin);
                    echo json_encode(['status' => 'success', 'message' => 'Administrateur créé avec succès !']);
                    exit();

                } catch (Exception $e) {
                    echo json_encode(['status' => 'error', 'message' => 'Une erreur s\'est produite : ' . $e->getMessage()]);
                    exit();
                }
            }
        }

        echo json_encode(['status' => 'error', 'message' => $erreurs ? implode(', ', $erreurs) : 'Erreur inconnue.']);
        exit();
    }

    public function updateAdminRole() {
        $this->checkIfSuperAdmin();
        $erreurs = [];
        $this->model('Admin');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (empty($_POST['admin_id']) || empty($_POST['new_role'])) {
                $erreurs[] = "L'ID de l'administrateur et le nouveau rôle sont requis.";
            } else {
                try {
                    $admin = new AdminModel();
                    $adminToUpdate = $admin->first(['id' => $_POST['admin_id']]);

                    if (!$adminToUpdate) {
                        $erreurs[] = "Administrateur non trouvé.";
                    } else if ($_POST['admin_id'] == $_SESSION['admin_id']) {
                        $erreurs[] = "Vous ne pouvez pas modifier votre propre rôle.";
                    } else {
                        // Verify the new role is valid
                        $validRoles = ['ADMIN', 'SUPER_ADMIN'];
                        if (!in_array($_POST['new_role'], $validRoles)) {
                            $erreurs[] = "Rôle invalide.";
                        } else {
                            $admin->update($_POST['admin_id'], ['role' => $_POST['new_role']]);
                            echo json_encode(['status' => 'success', 'message' => 'Rôle mis à jour avec succès !']);
                            exit();
                        }
                    }
                } catch (Exception $e) {
                    echo json_encode(['status' => 'error', 'message' => 'Une erreur s\'est produite : ' . $e->getMessage()]);
                    exit();
                }
            }
        }

        echo json_encode(['status' => 'error', 'message' => $erreurs ? implode(', ', $erreurs) : 'Erreur inconnue.']);
        exit();
    }

    private function validateAdminData($post) {
        $erreurs = [];

        $champsObligatoires = ['nom_user', 'email', 'mot_de_passe'];
        foreach ($champsObligatoires as $champ) {
            if (empty($post[$champ])) {
                $erreurs[] = ucfirst($champ) . " est requis.";
            }
        }

        if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
            $erreurs[] = "Adresse e-mail invalide.";
        }

        $admin = new AdminModel();
        if ($admin->first(['email' => $post['email']])) {
            $erreurs[] = "Cette adresse e-mail est déjà utilisée.";
        }

        if (strlen($post['mot_de_passe']) < 6) {
            $erreurs[] = "Le mot de passe doit contenir au moins 6 caractères.";
        }

        if (isset($post['role']) && !in_array($post['role'], ['ADMIN', 'SUPER_ADMIN'])) {
            $erreurs[] = "Rôle invalide.";
        }

        return $erreurs;
    }

    public function signOut() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        echo json_encode(['status' => 'success', 'message' => 'Déconnexion réussie !']);
        exit();
    }

    public function checkIfAdminOrSuperAdmin(){
        if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_role']) || ($_SESSION['admin_role'] !== 'ADMIN' && $_SESSION['admin_role'] !== 'SUPER_ADMIN')) {
            echo json_encode(['status' => 'error', 'message' => 'Accès non autorisé']);
            exit();
        }
    }

    // Membre management

    public function approveMember(){
        $this->checkIfAdminOrSuperAdmin();
        $erreurs = [];
        $this->model('Membre');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (empty($_POST['membre_id'])) {
                $erreurs[] = "ID du membre requis.";
            } else {
                try {
                    $membre = new MembreModel();
                    $membreToUpdate = $membre->first(['id' => $_POST['membre_id']]);

                    if (!$membreToUpdate) {
                        $erreurs[] = "Membre non trouvé.";
                    } else {
                        $membre->update($_POST['membre_id'], ['is_approved' => 1]);
                        echo json_encode(['status' => 'success', 'message' => 'Membre approuvé avec succès !']);
                        exit();
                    }
                } catch (Exception $e) {
                    echo json_encode(['status' => 'error', 'message' => 'Une erreur s\'est produite : ' . $e->getMessage()]);
                    exit();
                }
            }
        }

        echo json_encode(['status' => 'error', 'message' => $erreurs ? implode(', ', $erreurs) : 'Erreur inconnue.']);
        exit();
    }

    public function deleteMember(){
        $this->checkIfAdminOrSuperAdmin();
        $erreurs = [];
        $this->model('Membre');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (empty($_POST['membre_id'])) {
                $erreurs[] = "ID du membre requis.";
            } else {
                try {
                    $membre = new MembreModel();
                    $membreToDelete = $membre->first(['id' => $_POST['membre_id']]);

                    if (!$membreToDelete) {
                        $erreurs[] = "Membre non trouvé.";
                    } else {
                        $membre->delete($_POST['membre_id']);
                        echo json_encode(['status' => 'success', 'message' => 'Membre supprimé avec succès !']);
                        exit();
                    }
                } catch (Exception $e) {
                    echo json_encode(['status' => 'error', 'message' => 'Une erreur s\'est produite : ' . $e->getMessage()]);
                    exit();
                }
            }
        }

        echo json_encode(['status' => 'error', 'message' => $erreurs ? implode(', ', $erreurs) : 'Erreur inconnue.']);
        exit();
    }

    public function getAllMembers(){
        $this->checkIfAdminOrSuperAdmin();
        $this->model('Membre');
        $membre = new MembreModel();
        $membres = $membre->getAllMembers();
        echo json_encode(['status' => 'success', 'data' => $membres]);
        exit();
    }

    public function getMemberDetails(){
        $this->checkIfAdminOrSuperAdmin();
        $erreurs = [];
        $this->model('Membre');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (empty($_POST['membre_id'])) {
                $erreurs[] = "ID du membre requis.";
            } else {
                $membre = new MembreModel();
                $membreDetails = $membre->getMemberById($_POST['membre_id']);
                echo json_encode(['status' => 'success', 'data' => $membreDetails]);
                exit();
            }
        }

        echo json_encode(['status' => 'error', 'message' => $erreurs ? implode(', ', $erreurs) : 'Erreur inconnue.']);
        exit();
    }

    // Partenaire management

    public function addCategory(){
        $this->checkIfAdminOrSuperAdmin();
        $erreurs = [];
        $this->model('Categorie');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $categorie = new CategorieModel();

            if (empty($_POST['nom'])) {
                $erreurs[] = "Nom de la catégorie requis.";
            }

            if (empty($erreurs)) {
                try {
                    $donneesCategorie = [
                        'nom' => $_POST['nom']
                    ];

                    $categorie->insert($donneesCategorie);

                    echo json_encode(['status' => 'success', 'message' => 'Catégorie ajoutée avec succès !']);
                    exit();
                } catch (Exception $e) {
                    $erreurs[] = "Une erreur s'est produite lors de l'ajout de la catégorie : " . $e->getMessage();
                }
            }
        }

        echo json_encode(['status' => 'error', 'message' => $erreurs ? implode(', ', $erreurs) : 'Erreur inconnue.']);
        exit();
    }

    public function addPartner() {
        $this->checkIfAdminOrSuperAdmin();
        $erreurs = [];
        $this->model('Partenaire');
        $this->model('Categorie');
    
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $partenaire = new PartenaireModel();
            $categorie = new CategorieModel();
    
            $champsObligatoires = ['nom', 'ville', 'adresse', 'numero_de_telephone', 'email', 'categorie_id'];
            foreach ($champsObligatoires as $champ) {
                if (empty($_POST[$champ])) {
                    $erreurs[] = ucfirst($champ) . " est requis.";
                }
            }
    
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $erreurs[] = "Adresse e-mail invalide.";
            }
    
            if (!$categorie->getCategorieById($_POST['categorie_id'])) {
                $erreurs[] = "Catégorie invalide.";
            }
    
            if (!isset($_FILES['logo']) || $_FILES['logo']['error'] !== 0) {
                $erreurs[] = "Le logo est requis.";
            }
    
            if (empty($erreurs)) {
                try {
                    $logoPath = handleFileUpload($_FILES['logo'], 'logos/');
    
                    $donneesPartenaire = [
                        'nom' => $_POST['nom'],
                        'ville' => $_POST['ville'],
                        'adresse' => $_POST['adresse'],
                        'numero_de_telephone' => $_POST['numero_de_telephone'],
                        'email' => $_POST['email'],
                        'site_web' => $_POST['site_web'] ?? null,
                        'logo' => $logoPath,
                        'categorie_id' => $_POST['categorie_id'],
                        'statut' => 'ACTIF'
                    ];
    
                    $partenaire->insert($donneesPartenaire);
    
                    echo json_encode(['status' => 'success', 'message' => 'Partenaire ajouté avec succès !']);
                    exit();
                } catch (Exception $e) {
                    $erreurs[] = "Une erreur s'est produite lors de l'ajout du partenaire : " . $e->getMessage();
                }
            }
        }
    
        echo json_encode(['status' => 'error', 'message' => $erreurs ? implode(', ', $erreurs) : 'Erreur inconnue.']);
        exit();
    }

    public function updatePartnerStatus(){
        $this->checkIfAdminOrSuperAdmin();
        $erreurs = [];
        $this->model('Partenaire');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (empty($_POST['partenaire_id']) || empty($_POST['new_status'])) {
                $erreurs[] = "ID du partenaire et le nouveau statut sont requis.";
            } else {
                try {
                    $partenaire = new PartenaireModel();
                    $partenaireToUpdate = $partenaire->first(['id' => $_POST['partenaire_id']]);

                    if (!$partenaireToUpdate) {
                        $erreurs[] = "Partenaire non trouvé.";
                    } else {
                        // Verify the new status is valid
                        $validStatuses = ['ACTIF', 'INACTIF'];
                        if (!in_array($_POST['new_status'], $validStatuses)) {
                            $erreurs[] = "Statut invalide.";
                        } else {
                            $partenaire->update($_POST['partenaire_id'], ['statut' => $_POST['new_status']]);
                            echo json_encode(['status' => 'success', 'message' => 'Statut mis à jour avec succès !']);
                            exit();
                        }
                    }
                } catch (Exception $e) {
                    echo json_encode(['status' => 'error', 'message' => 'Une erreur s\'est produite : ' . $e->getMessage()]);
                    exit();
                }
            }
        }

        echo json_encode(['status' => 'error', 'message' => $erreurs ? implode(', ', $erreurs) : 'Erreur inconnue.']);
        exit();
    }

    public function deletePartner(){
        $this->checkIfAdminOrSuperAdmin();
        $erreurs = [];
        $this->model('Partenaire');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (empty($_POST['partenaire_id'])) {
                $erreurs[] = "ID du partenaire requis.";
            } else {
                try {
                    $partenaire = new PartenaireModel();
                    $partenaireToDelete = $partenaire->first(['id' => $_POST['partenaire_id']]);

                    if (!$partenaireToDelete) {
                        $erreurs[] = "Partenaire non trouvé.";
                    } else {
                        $partenaire->delete($_POST['partenaire_id']);
                        echo json_encode(['status' => 'success', 'message' => 'Partenaire supprimé avec succès !']);
                        exit();
                    }
                } catch (Exception $e) {
                    echo json_encode(['status' => 'error', 'message' => 'Une erreur s\'est produite : ' . $e->getMessage()]);
                    exit();
                }
            }
        }

        echo json_encode(['status' => 'error', 'message' => $erreurs ? implode(', ', $erreurs) : 'Erreur inconnue.']);
        exit();
    }

    public function editPartnerInfos(){
        $this->checkIfAdminOrSuperAdmin();
        $erreurs = [];
        $this->model('Partenaire');
        $this->model('Categorie');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $partenaire = new PartenaireModel();
            $categorie = new CategorieModel();

            $champsObligatoires = ['partenaire_id', 'nom', 'ville', 'adresse', 'numero_de_telephone', 'email', 'categorie_id'];
            foreach ($champsObligatoires as $champ) {
                if (empty($_POST[$champ])) {
                    $erreurs[] = ucfirst($champ) . " est requis.";
                }
            }

            if (!$partenaire->getPartenaireById($_POST['partenaire_id'])) {
                $erreurs[] = "Partenaire non trouvé.";
            }

            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $erreurs[] = "Adresse e-mail invalide.";
            }

            if (!$categorie->getCategorieById($_POST['categorie_id'])) {
                $erreurs[] = "Catégorie invalide.";
            }

            if (empty($erreurs)) {
                try {
                    $donneesPartenaire = [
                        'nom' => $_POST['nom'],
                        'ville' => $_POST['ville'],
                        'adresse' => $_POST['adresse'],
                        'numero_de_telephone' => $_POST['numero_de_telephone'],
                        'email' => $_POST['email'],
                        'site_web' => $_POST['site_web'] ?? null,
                        'categorie_id' => $_POST['categorie_id']
                    ];

                    $partenaire->update($_POST['partenaire_id'], $donneesPartenaire);

                    echo json_encode(['status' => 'success', 'message' => 'Informations du partenaire mises à jour avec succès !']);
                    exit();
                } catch (Exception $e) {
                    $erreurs[] = "Une erreur s'est produite lors de la mise à jour des informations du partenaire : " . $e->getMessage();
                }
            }
        }

        echo json_encode(['status' => 'error', 'message' => $erreurs ? implode(', ', $erreurs) : 'Erreur inconnue.']);
        exit();
    }

    public function getAllPartners(){
        $this->checkIfAdminOrSuperAdmin();
        $this->model('Partenaire');
        $partenaire = new PartenaireModel();
        $partenaires = $partenaire->getAllPartenaires();
        echo json_encode(['status' => 'success', 'data' => $partenaires]);
        exit();
    }

    public function getPartnerDetails(){
        $this->checkIfAdminOrSuperAdmin();
        $erreurs = [];
        $this->model('Partenaire');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (empty($_POST['partenaire_id'])) {
                $erreurs[] = "ID du partenaire requis.";
            } else {
                $partenaire = new PartenaireModel();
                $partenaireDetails = $partenaire->getPartenaireById($_POST['partenaire_id']);
                echo json_encode(['status' => 'success', 'data' => $partenaireDetails]);
                exit();
            }
        }

        echo json_encode(['status' => 'error', 'message' => $erreurs ? implode(', ', $erreurs) : 'Erreur inconnue.']);
        exit();
    }

    
    
    

}