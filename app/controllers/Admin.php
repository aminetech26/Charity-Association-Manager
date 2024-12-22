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
}