<?php
//defined('ROOTPATH') OR exit('Access Denied!');
class AdminModel {
    use Model;
    
    protected $table = "Compte_Admin";
    protected $allowedColumns = [
        'nom_user',
        'email',
        'mot_de_passe',
        'created_by',
        'role'
    ];

    public function getAllAdmins($limit=10,$offset=0) {
        return $this->findAll($limit,$offset);
    }

    public function getAdminById($id) {
        return $this->where(['id' => $id]);
    }

    public function getAdminByEmail($email) {
        return $this->where(['email' => $email]);
    }

    public function getAdminByRole($role) {
        return $this->where(['role' => $role]);
    }

    public function getTotalAdminAccounts(){
        return $this->getTotalCount();
    }
    
}