<?php
class OffreModel{
    use Model;
    protected $table = 'offre';
    protected $allowedColumns = ['partenaire_id', 'type_offre', 'valeur', 'description', 'date_debut', 'date_fin', 'is_special','thumbnail_path'];

    public function getAllOffers(){
        return $this->findAll();
    }

    public function getOfferById($id){
        return $this->where(['id' => $id]);
    }

    public function getOffersByPartnerId($id){
        return $this->where(['partenaire_id' => $id]);
    }

    public function getSpecialOffers(){
        return $this->where(['is_special' => 1]);
    }

    public function getOffersByType($type){
        return $this->where(['type_offre' => $type]);
    }

    public function getOffersByTypeAndPartnerId($type, $id){
        return $this->where(['type_offre' => $type, 'partenaire_id' => $id]);
    }
}