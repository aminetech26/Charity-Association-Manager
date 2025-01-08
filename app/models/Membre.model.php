<?php
//defined('ROOTPATH') OR exit('Access Denied!');

class MembreModel{
    use Model;
    protected $table = "Compte_Membre";
    protected $allowedColumns = ['id','nom', 'prenom', 'email', 'mot_de_passe', 'photo', 'piece_identite', 'adresse', 'numero_de_telephone', 'abonnement_id','is_approved','qr_code','member_unique_id','created_at'];

    public function getApprovedMembersWithSubscriptionType($date_inscription, $searchTerm, $limit = 10, $offset = 0) {
        return $this->join(
            ['abonnement' => ['id', 'type_abonnement']],
            ['abonnement' => 'abonnement.id = Compte_Membre.abonnement_id'],
            [
                'type' => 'LEFT',
                'limit' => $limit,
                'offset' => $offset,
                'order_column' => 'Compte_Membre.created_at',
                'order_type' => 'ASC',
                'where' => ['Compte_Membre.is_approved' => 1],
                'search' => [
                    'Compte_Membre.nom' => $searchTerm,
                    'Compte_Membre.created_at' => $date_inscription
                ]
            ]
        );
    }

    public function getTotalJoinMembers($date_inscription, $searchTerm) {
        return $this->getJoinTotalCount(
            ['abonnement' => ['id', 'type_abonnement']],
            ['abonnement' => 'abonnement.id = Compte_Membre.abonnement_id'],
            [
                'where' => ['Compte_Membre.is_approved' => 1],
                'search' => [
                    'Compte_Membre.nom' => $searchTerm,
                    'Compte_Membre.created_at' => $date_inscription
                ]
            ]
        );
    }


    public function getNonApprovedMembersWithSubscription($limit = 10, $offset = 0) {
        return $this->join(
            ['abonnement' => ['id', 'recu_paiement','type_abonnement']],
            ['abonnement' => 'Compte_Membre.abonnement_id = abonnement.id'],
            [
                'type' => 'LEFT',
                'limit' => $limit,
                'offset' => $offset,
                'order_column' => 'Compte_Membre.created_at',
                'order_type' => 'ASC',
                'where' => ['Compte_Membre.is_approved' => 0],
            ]
        );
    }

    public function getTotalJoinRegistrations() {
        return $this->getJoinTotalCount(
            ['abonnement' => ['id', 'recu_paiement']],
            ['abonnement' => 'abonnement.id = Compte_Membre.abonnement_id'],
            [
                'where' => ['Compte_Membre.is_approved' => 0]
            ]
        );
    }
    
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

    public function getTotal($conditions = []){
        return $this->getTotalCount($conditions);
    }

}