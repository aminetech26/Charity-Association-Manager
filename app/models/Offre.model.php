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

    public function getFilteredOffers($filters, $limit = 10, $offset = 0) {
        $conditions = ['Partenaire.statut' => 'ACTIF'];
        
        if (!empty($filters['type_offre'])) {
            $conditions['offre.type_offre'] = $filters['type_offre'];
        }
        
        if (!empty($filters['partenaire_id'])) {
            $conditions['offre.partenaire_id'] = $filters['partenaire_id'];
        }

        $orderBy = match($filters['sort'] ?? 'date_desc') {
            'date_asc' => 'offre.date_debut ASC',
            'value_desc' => 'CAST(REPLACE(offre.valeur, "%", "") AS DECIMAL(10,2)) DESC',
            'value_asc' => 'CAST(REPLACE(offre.valeur, "%", "") AS DECIMAL(10,2)) ASC',
            default => 'offre.date_debut DESC'
        };

        return $this->join(
            ['partenaire' => ['nom']],
            ['partenaire' => 'offre.partenaire_id = partenaire.id'],
            [
                'type' => 'LEFT',
                'limit' => $limit,
                'offset' => $offset,
                'where' => $conditions,
                'order_by' => $orderBy
            ]
        );
    }

    public function getTotalFilteredOffers($filters) {
        $conditions = ['Partenaire.statut' => 'ACTIF'];
        
        if (!empty($filters['type_offre'])) {
            $conditions['offre.type_offre'] = $filters['type_offre'];
        }
        
        if (!empty($filters['partenaire_id'])) {
            $conditions['offre.partenaire_id'] = $filters['partenaire_id'];
        }

        return $this->getJoinTotalCount(
            ['partenaire' => ['nom']],
            ['partenaire' => 'offre.partenaire_id = partenaire.id'],
            ['where' => $conditions]
        );
    }

}