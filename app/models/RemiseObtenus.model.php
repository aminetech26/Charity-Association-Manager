<?php
class RemiseObtenusModel{
    use Model;
    protected $table = 'remise_obtenus';
    protected $allowedColumns = ['compte_membre_id', 'offre_id','date_benefice'];

    public function getAllDiscounts(){
        return $this->findAll();
    }

    public function getDiscountById($id){
        return $this->where(['id' => $id]);
    }

    public function getDiscountsByOfferId($id){
        return $this->where(['offre_id' => $id]);
    }
}