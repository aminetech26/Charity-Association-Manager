<?php
class OffreModel{
    use Model;
    protected $table = 'offre';
    protected $allowedColumns = ['id','partenaire_id', 'type_offre', 'valeur', 'description', 'date_debut', 'date_fin', 'is_special','thumbnail_path'];

    public function getAllOffers($limit = 10, $offset = 0){
        return $this->join(
            ['partenaire' => ['nom']],
            ['partenaire' => 'offre.partenaire_id = partenaire.id'],
            [
                'type' => 'LEFT',
                'limit' => $limit,
                'offset' => $offset,
                'where' => ['Partenaire.statut' => 'ACTIF'],
            ]
        );
    }

    public function getTotalJoinOffers() {
        return $this->getJoinTotalCount(
            ['partenaire' => ['nom']],
            ['partenaire' => 'offre.partenaire_id = partenaire.id'],
            [
                'where' => ['Partenaire.statut' => 'ACTIF'],
            ]
        );
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