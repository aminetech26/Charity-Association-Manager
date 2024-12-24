<?php

class BenevolatsModel{

    use Model;
    
    protected $table = 'benevolats';
    protected $allowedColumns = ['compte_membre_id', 'evenement_id', 'statut'];
    
    public function getAllBenevolats(){
        return $this->findAll();
    }

    public function getBenevolatById($id){
        return $this->find(['id' => $id]);
    }

    public function getBenevolatByMembreId($membre_id){
        return $this->find(['compte_membre_id' => $membre_id]);
    }

}