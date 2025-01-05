<?php
class Admin {
    use Controller;

    public function index()
    {
        $this->view("admin_signin","admin");
        $view = new Admin_login_view();
        $view->page_head('Connexion administrateur');
        $view->show_login_page();
        $view->page_footer();
    }

    public function dashboard()
    {
        $this->checkIfAdminOrSuperAdmin();
        $this->view("admin_dashboard","admin");
        $view = new Admin_dashboard_view();
        $view->page_head('Tableau de bord administrateur');
        $view->show_dashboard_page();
        $view->page_footer();
    }

    public function partenaire_content(){
        $this->checkIfAdminOrSuperAdmin();
        $content = $this->view("partenaire","admin", true);
        echo $content;
    }

    public function members_content(){
        $this->checkIfAdminOrSuperAdmin();
        $content = $this->view("membres","admin", true);
        echo $content;
    }

    public function donations_content(){
        $this->checkIfAdminOrSuperAdmin();
        $content = $this->view("dons","admin", true);
        echo $content;
    }

    public function payments_content(){
        $this->checkIfAdminOrSuperAdmin();
        $content = $this->view("paiement","admin", true);
        echo $content;
    }

    public function aides_content(){
        $this->checkIfAdminOrSuperAdmin();
        $content = $this->view("aides","admin", true);
        echo $content;
    }

    public function notifications_content(){
        $this->checkIfAdminOrSuperAdmin();
        $content = $this->view("notifications","admin", true);
        echo $content;
    }

    public function groups_content(){
        $this->checkIfAdminOrSuperAdmin();
        $content = $this->view("groupes","admin", true);
        echo $content;
    }

    public function settings_content(){
        $this->checkIfSuperAdmin();
        $content = $this->view("parametres_site","admin", true);
        echo $content;
    }



    private function checkIfSuperAdmin() {
        if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_role']) || $_SESSION['admin_role'] !== 'SUPER_ADMIN') {
            redirect('admin/Admin/index');
        }
    }

    public function signIn() {
        $erreurs = [];
        $this->model('Admin');
    
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $admin = new AdminModel();
    
            if (empty($_POST['nom_user']) || empty($_POST['mot_de_passe'])) {
                echo json_encode([
                    'status' => 'error',
                    'message' => "Le nom d'utilisateur et le mot de passe sont requis."
                ]);
                exit();
            }
    
            $utilisateur = $admin->first(['nom_user' => $_POST['nom_user']]);
    
            if ($utilisateur && password_verify($_POST['mot_de_passe'], $utilisateur->mot_de_passe)) {
                $_SESSION['admin_id'] = $utilisateur->id;
                $_SESSION['admin_nom'] = $utilisateur->nom_user;
                $_SESSION['admin_email'] = $utilisateur->email;
                $_SESSION['admin_role'] = $utilisateur->role;
    
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Connexion réussie !',
                ]);
                exit();
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => "Nom d'utilisateur ou mot de passe incorrect."
                ]);
                exit();
            }
        }
    
        echo json_encode([
            'status' => 'error',
            'message' => 'Méthode non autorisée.'
        ]);
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
        redirect('admin/Admin/index');
        exit();
    }

    public function checkIfAdminOrSuperAdmin() {
        $allowedRoles = ['ADMIN', 'SUPER_ADMIN'];
    
        if (empty($_SESSION['admin_id']) || empty($_SESSION['admin_role']) || !in_array($_SESSION['admin_role'], $allowedRoles)) {
            redirect('admin/Admin/index');
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

    // Category management

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

    public function deleteCategory(){
        $this->checkIfAdminOrSuperAdmin();
        $erreurs = [];
        $this->model('Categorie');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (empty($_POST['category_id'])) {
                $erreurs[] = "ID de la catégorie requis.";
            } else {
                try {
                    $categorie = new CategorieModel();
                    $categorieToDelete = $categorie->first(['id' => $_POST['category_id']]);

                    if (!$categorieToDelete) {
                        $erreurs[] = "Catégorie non trouvée.";
                    } else {
                        $categorie->delete($_POST['category_id']);
                        echo json_encode(['status' => 'success', 'message' => 'Catégorie supprimée avec succès !']);
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

    public function getAllCategories(){
        $this->model('Categorie');
        $categorie = new CategorieModel();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $offset = ($page - 1) * $limit;

        $categories = $categorie->getAllCategories($limit, $offset);

        $total = $categorie->getTotalCategories();
        if(!$categories){
            $categories = [];
        }
        
        echo json_encode([
            'status' => 'success',
            'data' => $categories,
            'pagination' => [
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'total_pages' => ceil($total / $limit)
            ]
        ]);
    }

    // Partenaire management


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

    public function deletePartners() {
        $this->checkIfAdminOrSuperAdmin();
        $erreurs = [];
        $this->model('Partenaire');
    
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $data = json_decode(file_get_contents('php://input'), true);
            $ids = $data['ids'] ?? [];
    
            if (empty($ids)) {
                $erreurs[] = "Aucun partenaire sélectionné.";
            } else {
                try {
                    $partenaire = new PartenaireModel();
                    foreach ($ids as $id) {
                        $partenaireToDelete = $partenaire->first(['id' => $id]);
                        if (!$partenaireToDelete) {
                            $erreurs[] = "Partenaire avec l'ID $id non trouvé.";
                        } else {
                            $partenaire->delete($id);
                        }
                    }
    
                    if (empty($erreurs)) {
                        echo json_encode(['status' => 'success', 'message' => 'Partenaires supprimés avec succès !']);
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

    

    public function getAllPartners() {
        $this->model('Partenaire');
        $partenaire = new PartenaireModel();
    
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $nom = isset($_GET['nom']) ? $_GET['nom'] : null;
        $categorie_id = isset($_GET['categorie_id']) ? $_GET['categorie_id'] : null;
        $ville = isset($_GET['ville']) ? $_GET['ville'] : null;
        $offset = ($page - 1) * $limit;
        
        $searchFields = [];
        $exactMatchFields = [];

        if ($nom !== null && $nom !== '' && $nom !== 'null') {
            $searchFields['nom'] = $nom;
        }
        if ($categorie_id !== null && $categorie_id !== '' && $categorie_id !== 'null') {
            $searchFields['categorie_id'] = $categorie_id;
            $exactMatchFields[] = 'categorie_id';
            $conditons['categorie_id'] = $categorie_id;
        }
        if ($ville !== null && $ville !== '' && $ville !== 'null') {
            $searchFields['ville'] = $ville;
        }

        $partenaires = $partenaire->search($searchFields, $exactMatchFields, $limit, $offset);

        $total = $partenaire->getTotalPartenaires($conditons);
        if(!$partenaires){
            $partenaires = [];
        }
        
        echo json_encode([
            'status' => 'success',
            'data' => $partenaires,
            'pagination' => [
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'total_pages' => ceil($total / $limit)
            ]
        ]);
        
        exit();
    }

    public function getPartnerDetails(){
        $this->checkIfAdminOrSuperAdmin();
        $erreurs = [];
        $this->model('Partenaire');

        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            if (empty($_GET['partenaire_id'])) {
                $erreurs[] = "ID du partenaire requis.";
            } else {
                $partenaire = new PartenaireModel();
                $partenaireDetails = $partenaire->getPartenaireById($_GET['partenaire_id']);
                echo json_encode(['status' => 'success', 'data' => $partenaireDetails]);
                exit();
            }
        }

        echo json_encode(['status' => 'error', 'message' => $erreurs ? implode(', ', $erreurs) : 'Erreur inconnue.']);
        exit();
    }

    public function createPartnerAccount(){
        $this->checkIfAdminOrSuperAdmin();
        $erreurs = [];
        $this->model('ComptePartenaire');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $comptePartenaire = new ComptePartenaireModel();
            $erreurs = $this->validatePartnerData($_POST);

            if (empty($erreurs)) {
                try {
                    $donneesComptePartenaire = [
                        'partenaire_id' => $_POST['partenaire_id'],
                        'email' => $_POST['email-compte'],
                        'mot_de_passe' => password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT),
                        'created_by' => $_SESSION['admin_id'],
                        'statut' => 'ACTIVE'
                    ];

                    $comptePartenaire->insert($donneesComptePartenaire);
                    echo json_encode(['status' => 'success', 'message' => 'Compte partenaire créé avec succès !']);
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

    public function validatePartnerData($post) {
        $erreurs = [];
        $this->model('Partenaire');
        $comptePartenaire = new ComptePartenaireModel();
        $partenaire = new PartenaireModel();

        $champsObligatoires = ['partenaire_id', 'email-compte', 'mot_de_passe'];
        foreach ($champsObligatoires as $champ) {
            if (empty($post[$champ])) {
                $erreurs[] = ucfirst($champ) . " est requis.";
            }
        }

        if(!$partenaire->getPartenaireById($post['partenaire_id'])){
            $erreurs[] = "Partenaire non trouvé.";
        }

        if (!filter_var($post['email-compte'], FILTER_VALIDATE_EMAIL)) {
            $erreurs[] = "Adresse e-mail invalide.";
        }

        if ($comptePartenaire->first(['email' => $post['email-compte']])) {
            $erreurs[] = "Cette adresse e-mail est déjà utilisée.";
        }

        if (strlen($post['mot_de_passe']) < 6) {
            $erreurs[] = "Le mot de passe doit contenir au moins 6 caractères.";
        }

        return $erreurs;
    }

    public function updateStatutComptePartenaire(){
        $this->checkIfAdminOrSuperAdmin();
        $erreurs = [];
        $this->model('ComptePartenaire');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (empty($_POST['compte_partenaire_id']) || empty($_POST['new_statut'])) {
                $erreurs[] = "ID du compte partenaire et le nouveau statut sont requis.";
            } else {
                try {
                    $comptePartenaire = new ComptePartenaireModel();
                    $comptePartenaireToUpdate = $comptePartenaire->first(['id' => $_POST['compte_partenaire_id']]);

                    if (!$comptePartenaireToUpdate) {
                        $erreurs[] = "Compte partenaire non trouvé.";
                    } else {
                        // Verify the new status is valid
                        $validStatuses = ['ACTIVE', 'BLOCKED'];
                        if (!in_array($_POST['new_statut'], $validStatuses)) {
                            $erreurs[] = "Statut invalide.";
                        } else {
                            $comptePartenaire->update($_POST['compte_partenaire_id'], ['statut' => $_POST['new_statut']]);
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

    public function getAllComptesPartenaires(){
            $this->checkIfAdminOrSuperAdmin();
            $this->model('ComptePartenaire');
            $compte_partenaire = new ComptePartenaireModel();
        
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            $offset = ($page - 1) * $limit;
    
            $compte_partenaires = $compte_partenaire->getAllPartnerAccounts($limit, $offset);
    
            $total = $compte_partenaire->getTotalPartnerAccounts();
            if(!$compte_partenaires){
                $compte_partenaires = [];
            }
            
            echo json_encode([
                'status' => 'success',
                'data' => $compte_partenaires,
                'pagination' => [
                    'total' => $total,
                    'page' => $page,
                    'limit' => $limit,
                    'total_pages' => ceil($total / $limit)
                ]
            ]);
            
            exit();
        }

        public function deletePartnerAccount(){
            $this->checkIfAdminOrSuperAdmin();
            $erreurs = [];
            $this->model('ComptePartenaire');
        
            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                if (empty($_POST['compte_partenaire_id'])) {
                    $erreurs[] = "ID du compte partenaire requis.";
                } else {
                    try {
                        $comptePartenaire = new ComptePartenaireModel();
                        $comptePartenaireToDelete = $comptePartenaire->first(['id' => $_POST['compte_partenaire_id']]);
        
                        if (!$comptePartenaireToDelete) {
                            $erreurs[] = "Compte partenaire non trouvé.";
                        } else {
                            $comptePartenaire->delete($_POST['compte_partenaire_id']);
                            echo json_encode(['status' => 'success', 'message' => 'Compte partenaire supprimé avec succès !']);
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


    // Offer management

    public function addPartnerOffer(){
    $this->checkIfAdminOrSuperAdmin();
    $this->model('Offre');
    $this->model('Partenaire');

    $erreurs = [];

    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        $offre = new OffreModel();
        $partenaire = new PartenaireModel();

        $erreurs = $this->validateOfferData($_POST, $partenaire);

        if (empty($erreurs)) {
            try {
                $thumbnailPath = null;
                if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === 0) {
                    $thumbnailPath = handleFileUpload($_FILES['thumbnail'], 'thumbnails/');
                }

                $donneesOffre = [
                    'partenaire_id' => $_POST['partenaire_id'],
                    'type_offre' => $_POST['type_offre'],
                    'valeur' => $_POST['valeur'],
                    'description' => $_POST['description'],
                    'date_debut' => $_POST['date_debut'],
                    'date_fin' => $_POST['date_fin'],
                    'is_special' => $_POST['is_special'] ?? 0,
                    'thumbnail_path' => $thumbnailPath ?? null
                ];

                $offre->insert($donneesOffre);

                echo json_encode(['status' => 'success', 'message' => 'Offre ajoutée avec succès']);
                exit();
            } catch (Exception $e) {
                $erreurs[] = "Une erreur s'est produite lors de l'ajout de l'offre : " . $e->getMessage();
            }
        }

        echo json_encode(['status' => 'error', 'errors' => $erreurs]);
        exit();
    }
    }

    private function validateOfferData($post){
        $erreurs = [];
        $this->model('Partenaire');
        $partenaireModel = new PartenaireModel();

        $champsObligatoires = ['partenaire_id', 'type_offre', 'valeur', 'description', 'date_debut', 'date_fin'];
        foreach ($champsObligatoires as $champ) {
            if (empty($post[$champ])) {
                $erreurs[] = ucfirst($champ) . " est requis.";
                return $erreurs;
            }
        }

        if (!$partenaireModel->getPartenaireById($post['partenaire_id'])) {
            $erreurs[] = "Partenaire non trouvé.";
            return $erreurs;
        }

        if (!in_array($post['type_offre'], ['CLASSIQUE', 'JEUNE', 'PREMIUM'])) {
            $erreurs[] = "Type d'offre invalide.";
            return $erreurs;
        }

        if (!empty($post['valeur']) && ($post['valeur'] <= 0 || $post['valeur'] >= 100)) {
            $erreurs[] = "La valeur de la remise doit être comprise entre 0 et 100.";
            return $erreurs;
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $post['date_debut'])) {
            $erreurs[] = "Date de début invalide. Format attendu : YYYY-MM-DD.";
            return $erreurs;
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $post['date_fin'])) {
            $erreurs[] = "Date de fin invalide. Format attendu : YYYY-MM-DD.";
            return $erreurs;
        }

        if (strtotime($post['date_debut']) > strtotime($post['date_fin'])) {
            $erreurs[] = "La date de début doit être antérieure à la date de fin.";
        }

        return $erreurs;
    }

    public function deleteOffer(){
        $this->checkIfAdminOrSuperAdmin();
        $erreurs = [];
        $this->model('Offre');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (empty($_POST['offre_id'])) {
                $erreurs[] = "ID de l'offre requis.";
            } else {
                try {
                    $offre = new OffreModel();
                    $offreToDelete = $offre->first(['id' => $_POST['offre_id']]);

                    if (!$offreToDelete) {
                        $erreurs[] = "Offre non trouvée.";
                    } else {
                        $offre->delete($_POST['offre_id']);
                        echo json_encode(['status' => 'success', 'message' => 'Offre supprimée avec succès !']);
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

    public function updateOffer(){
        $this->checkIfAdminOrSuperAdmin();
        $erreurs = [];
        $this->model('Offre');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $offre = new OffreModel();
            $erreurs = $this->validateOfferData($_POST);
            if(!$offre->getOfferById($_POST['offre_id'])){
                $erreurs[] = "Offre non trouvée.";
            }
            if (empty($erreurs)) {
                try {
                    $donneesOffre = [
                        'partenaire_id' => $_POST['partenaire_id'],
                        'type_offre' => $_POST['type_offre'],
                        'valeur' => $_POST['valeur'],
                        'description' => $_POST['description'],
                        'date_debut' => $_POST['date_debut'],
                        'date_fin' => $_POST['date_fin'],
                        'is_special' => $_POST['is_special'] ?? 0
                    ];

                    $offre->update($_POST['offre_id'], $donneesOffre);
                    echo json_encode(['status' => 'success', 'message' => 'Offre mise à jour avec succès !']);
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

    public function getAllOffers(){
        $this->checkIfAdminOrSuperAdmin();
        $this->model('Offre');
        $offre = new OffreModel();
        $offres = $offre->getAllOffers();
        echo json_encode(['status' => 'success', 'data' => $offres]);
        exit();
    }

    public function getOfferDetails(){
        $this->checkIfAdminOrSuperAdmin();
        $erreurs = [];
        $this->model('Offre');

        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            if (empty($_GET['offre_id'])) {
                $erreurs[] = "ID de l'offre requis.";
            } else {
                $offre = new OffreModel();
                $offreDetails = $offre->getOfferById($_GET['offre_id']);
                echo json_encode(['status' => 'success', 'data' => $offreDetails]);
                exit();
            }
        }

        echo json_encode(['status' => 'error', 'message' => $erreurs ? implode(', ', $erreurs) : 'Erreur inconnue.']);
        exit();
    }

    // Event management

    public function addEvent(){
        $this->checkIfAdminOrSuperAdmin();
        $erreurs = [];
        $this->model('Evenement');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $evenement = new EvenementModel();

            $champsObligatoires = ['titre', 'description', 'lieu', 'date_debut', 'date_fin'];
            foreach ($champsObligatoires as $champ) {
                if (empty($_POST[$champ])) {
                    $erreurs[] = ucfirst($champ) . " est requis.";
                }
            }

            if (!empty($_POST['date_debut']) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['date_debut'])) {
                $erreurs[] = "Date de début invalide. Format attendu : YYYY-MM-DD.";
            }

            if (!empty($_POST['date_fin']) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['date_fin'])) {
                $erreurs[] = "Date de fin invalide. Format attendu : YYYY-MM-DD.";
            }

            if (empty($erreurs)) {
                try {
                    $donneesEvenement = [
                        'titre' => $_POST['titre'],
                        'description' => $_POST['description'],
                        'lieu' => $_POST['lieu'],
                        'date_debut' => $_POST['date_debut'],
                        'date_fin' => $_POST['date_fin']
                    ];

                    $evenement->insert($donneesEvenement);

                    echo json_encode(['status' => 'success', 'message' => 'Événement ajouté avec succès !']);
                    exit();
                } catch (Exception $e) {
                    $erreurs[] = "Une erreur s'est produite lors de l'ajout de l'événement : " . $e->getMessage();
                }
            }
        }

        echo json_encode(['status' => 'error', 'message' => $erreurs ? implode(', ', $erreurs) : 'Erreur inconnue.']);
        exit();
    }

    public function deleteEvent(){
        $this->checkIfAdminOrSuperAdmin();
        $erreurs = [];
        $this->model('Evenement');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (empty($_POST['evenement_id'])) {
                $erreurs[] = "ID de l'événement requis.";
            } else {
                try {
                    $evenement = new EvenementModel();
                    $evenementToDelete = $evenement->first(['id' => $_POST['evenement_id']]);

                    if (!$evenementToDelete) {
                        $erreurs[] = "Événement non trouvé.";
                    } else {
                        $evenement->delete($_POST['evenement_id']);
                        echo json_encode(['status' => 'success', 'message' => 'Événement supprimé avec succès !']);
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

    public function editEvent(){
        $this->checkIfAdminOrSuperAdmin();
        $erreurs = [];
        $this->model('Evenement');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $evenement = new EvenementModel();

            $champsObligatoires = ['evenement_id', 'titre', 'description', 'lieu', 'date_debut', 'date_fin'];
            foreach ($champsObligatoires as $champ) {
                if (empty($_POST[$champ])) {
                    $erreurs[] = ucfirst($champ) . " est requis.";
                }
            }

            if (!empty($_POST['date_debut']) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['date_debut'])) {
                $erreurs[] = "Date de début invalide. Format attendu : YYYY-MM-DD.";
            }

            if (!empty($_POST['date_fin']) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['date_fin'])) {
                $erreurs[] = "Date de fin invalide. Format attendu : YYYY-MM-DD.";
            }

            if (empty($erreurs)) {
                try {
                    $donneesEvenement = [
                        'titre' => $_POST['titre'],
                        'description' => $_POST['description'],
                        'lieu' => $_POST['lieu'],
                        'date_debut' => $_POST['date_debut'],
                        'date_fin' => $_POST['date_fin']
                    ];

                    $evenement->update($_POST['evenement_id'], $donneesEvenement);

                    echo json_encode(['status' => 'success', 'message' => 'Événement mis à jour avec succès !']);
                    exit();
                } catch (Exception $e) {
                    $erreurs[] = "Une erreur s'est produite lors de la mise à jour de l'événement : " . $e->getMessage();
                }
            }
        }

        echo json_encode(['status' => 'error', 'message' => $erreurs ? implode(', ', $erreurs) : 'Erreur inconnue.']);
        exit();
    }

    // Benevolat management

    public function validerBenevolat(){
        $this->checkIfAdminOrSuperAdmin();
        $erreurs = [];
        $this->model('Benevolats');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (empty($_POST['benevolat_id'])) {
                $erreurs[] = "ID du bénévolat requis.";
            } else {
                try {
                    $benevolat = new BenevolatsModel();
                    $benevolatToValidate = $benevolat->first(['id' => $_POST['benevolat_id']]);

                    if (!$benevolatToValidate) {
                        $erreurs[] = "Bénévolat non trouvé.";
                    } else {
                        $benevolat->update($_POST['benevolat_id'], ['statut' => 'VALIDE']);
                        echo json_encode(['status' => 'success', 'message' => 'Bénévolat validé avec succès !']);
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

    public function refuserBenevolat(){
        $this->checkIfAdminOrSuperAdmin();
        $erreurs = [];
        $this->model('Benevolats');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (empty($_POST['benevolat_id'])) {
                $erreurs[] = "ID du bénévolat requis.";
            } else {
                try {
                    $benevolat = new BenevolatsModel();
                    $benevolatToValidate = $benevolat->first(['id' => $_POST['benevolat_id']]);

                    if (!$benevolatToValidate) {
                        $erreurs[] = "Bénévolat non trouvé.";
                    } else {
                        $benevolat->update($_POST['benevolat_id'], ['statut' => 'REFUSE']);
                        echo json_encode(['status' => 'success', 'message' => 'Bénévolat refusé par l\'admin !']);
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

    // Dons management

    public function validerDon(){
        $this->checkIfAdminOrSuperAdmin();
        $erreurs = [];
        $this->model('Dons');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (empty($_POST['don_id'])) {
                $erreurs[] = "ID du don requis.";
            } else {
                try {
                    $don = new DonsModel();
                    $donToValidate = $don->first(['id' => $_POST['don_id']]);

                    if (!$donToValidate) {
                        $erreurs[] = "Don non trouvé.";
                    } else {
                        $don->update($_POST['don_id'], ['statut' => 'VALIDE']);
                        echo json_encode(['status' => 'success', 'message' => 'Don validé avec succès !']);
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

    public function refuserDon(){
        $this->checkIfAdminOrSuperAdmin();
        $erreurs = [];
        $this->model('Dons');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (empty($_POST['don_id'])) {
                $erreurs[] = "ID du don requis.";
            } else {
                try {
                    $don = new DonsModel();
                    $donToValidate = $don->first(['id' => $_POST['don_id']]);

                    if (!$donToValidate) {
                        $erreurs[] = "Don non trouvé.";
                    } else {
                        $don->update($_POST['don_id'], ['statut' => 'REFUSE']);
                        echo json_encode(['status' => 'success', 'message' => 'Don refusé par l\'admin !']);
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

    // Notification management

    public function addNotification(){
        $this->checkIfAdminOrSuperAdmin();
        $erreurs = [];
        $this->model('Notification');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $notification = new NotificationModel();

            $champsObligatoires = ['titre','contenu','date_envoi', 'type'];
            foreach ($champsObligatoires as $champ) {
                if (empty($_POST[$champ])) {
                    $erreurs[] = ucfirst($champ) . " est requis.";
                }
            }

            if (!empty($_POST['date_envoi']) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['date_envoi'])) {
                $erreurs[] = "Date d'envoi invalide. Format attendu : YYYY-MM-DD.";
            }

            if(!in_array($_POST['type'], ['EVENEMENT', 'PROMOTION', 'NOUVELLE_OFFRE', 'RAPPEL', 'AUTRE'])){
                $erreurs[] = "Type de notification invalide.";
            }

            if (empty($erreurs)) {
                try {
                    $donneesNotification = [
                        'titre' => $_POST['titre'],
                        'contenu' => $_POST['contenu'],
                        'date_envoi' => $_POST['date_envoi'],
                        'is_sent' => 0,
                        'groupe_cible' => $_POST['groupe_cible'] ?? 0,
                        'type' => $_POST['type'],
                        'created_by' => $_SESSION['admin_id']
                    ];

                    $notification->insert($donneesNotification);

                    echo json_encode(['status' => 'success', 'message' => 'Notification ajoutée avec succès !']);
                    exit();
                } catch (Exception $e) {
                    $erreurs[] = "Une erreur s'est produite lors de l'ajout de la notification : " . $e->getMessage();
                }
            }

            
        }

        echo json_encode(['status' => 'error', 'message' => $erreurs ? implode(', ', $erreurs) : 'Erreur inconnue.']);
        exit();
    }

    public function deleteNotification(){
        $this->checkIfAdminOrSuperAdmin();
        $erreurs = [];
        $this->model('Notification');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (empty($_POST['notification_id'])) {
                $erreurs[] = "ID de la notification requis.";
            } else {
                try {
                    $notification = new NotificationModel();
                    $notificationToDelete = $notification->first(['id' => $_POST['notification_id']]);

                    if (!$notificationToDelete) {
                        $erreurs[] = "Notification non trouvée.";
                    } else {
                        $notification->delete($_POST['notification_id']);
                        echo json_encode(['status' => 'success', 'message' => 'Notification supprimée avec succès !']);
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

    // Abonnement management

    public function getAllSubscriptions(){
        $this->checkIfAdminOrSuperAdmin();
        $this->model('Abonnement');
        $abonnement = new AbonnementModel();
        $abonnements = $abonnement->getAllSubscriptions();
        echo json_encode(['status' => 'success', 'data' => $abonnements]);
        exit();
    }

    public function approveSubscription(){
        $this->checkIfAdminOrSuperAdmin();
        $erreurs = [];
        $this->model('Abonnement');

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (empty($_POST['abonnement_id'])) {
                $erreurs[] = "ID de l'abonnement requis.";
            } else {
                try {
                    if(!in_array($_POST['type_abonnement'], ['CLASSIQUE', 'JEUNE', 'PREMIUM'])){
                        $erreurs[] = "Type abonnement invalide.";
                    }
                    $abonnement = new AbonnementModel();
                    $abonnementToApprove = $abonnement->first(['id' => $_POST['abonnement_id']]);

                    if (!$abonnementToApprove) {
                        $erreurs[] = "Abonnement non trouvé.";
                    } else {
                        $abonnementUpdatedData = [
                            'type_abonnement' => $_POST['type_abonnement'],
                            'date_debut' => date('Y-m-d H:i:s'),
                            'date_fin' => date('Y-m-d H:i:s', strtotime('+1 year')),
                            'is_active' => 1,
                            'statut' => 'RENOUVELE'
                        ];
                        $abonnement->update($_POST['abonnement_id'], $abonnementUpdatedData);
                        echo json_encode(['status' => 'success', 'message' => 'Abonnement approuvé avec succès !']);
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
}