<?php
//defined('ROOTPATH') OR exit('Access Denied!');

class MembreModel{
    use Model;
    protected $table = "Compte_Membre";
    protected $allowedColumns = ['nom', 'prenom', 'email', 'mot_de_passe', 'photo', 'piece_identite', 'adresse', 'numero_de_telephone', 'abonnement_id','is_approved','qr_code','member_unique_id','created_at'];

    public function getApprovedMembers($limit = 10,$offset = 0){
        return $this->where(['is_approved' => 1],[],$limit,$offset);
    }

    public function getMemberById($id){
        return $this->where(['id' => $id]);
    }

    public function getMembersWithSubscription(){
        return $this->query("SELECT * FROM Compte_Membre WHERE abonnement_id IS NOT NULL");
    }

    public function getMembersWithoutSubscription(){
        return $this->query("SELECT * FROM Compte_Membre WHERE abonnement_id IS NULL");
    }

    public function getMembersNotApproved($limit = 10,$offset = 0){
        return $this->where(['is_approved' => 0],[],$limit,$offset);
    }

    public function getTotalMembres($conditions = []){
        return $this->getTotalCount($conditions);
    }

}