<?php
class DonsModel{
    use Model;
    protected $table = 'dons';
    protected $allowedColumns = ['montant', 'compte_membre_id', 'date', 'est_tracable','statut','recu_paiement'];

    public function getAllDons(){
        return $this->findAll();
    }

    public function getDonById($id){
        return $this->find(['id' => $id]);
    }

    public function getDonByMembreId($membre_id){
        return $this->find(['compte_membre_id' => $membre_id]);
    }
}