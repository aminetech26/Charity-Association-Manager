<?php
class ComptePartenaireModel{
    use Model;

    protected $table = "Compte_Partenaire";
    protected $allowedColumns = ['partenaire_id', 'email', 'mot_de_passe', 'created_by', 'statut'];

    public function getAllPartnerAccounts($limit = 10, $offset = 0){
        return $this->findAll($limit, $offset);
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

    public function getTotalPartnerAccounts(){
        return $this->getTotalCount();
    }

}