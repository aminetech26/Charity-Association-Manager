<?php
class ComptePartenaireModel{
    use Model;

    protected $table = "Compte_Partenaire";
    protected $allowedColumns = ['partenaire_id', 'email', 'mot_de_passe', 'created_by', 'statut'];

    public function getAllPartnerAccounts(){
        return $this->findAll();
    }

    public function getPartnerAccountById($id){
        return $this->where(['id' => $id]);
    }

    public function getPartnerAccountByEmail($email){
        return $this->where(['email' => $email]);
    }

    public function getPartnerAccountByStatus($status){
        return $this->where(['statut' => $status]);
    }

}