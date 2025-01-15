<?php
class RemiseObtenusModel{
    use Model;
    protected $table = 'remise_obtenus';
    protected $allowedColumns = ['compte_membre_id', 'offre_id','date_benefice'];

    public function getAllDiscounts(){
        return $this->findAll();
    }

    public function getRemiseParPartenaire($id, $limit = 10, $offset = 0) {
        return $this->join(
            [
                'offre' => ['id', 'partenaire_id'],
                'compte_membre' => ['id', 'nom', 'prenom'],
                'remise_obtenus' => ['id', 'offre_id', 'compte_membre_id', 'date_benefice']
            ],
            [
                'offre' => 'offre.id = remise_obtenus.offre_id',
                'compte_membre' => 'compte_membre.id = remise_obtenus.compte_membre_id'
            ],
            [
                'offset' => $offset,
                'limit' => $limit,
                'type' => 'INNER',
                'where' => ['offre.partenaire_id' => $id],
                'order_column' => 'compte_membre.id',
                'order_type' => 'ASC'
            ]
        );
    }

    public function getTotalRemisesParPartenaire($id){
        return $this->getJoinTotalCount(
            [
                'offre' => ['id', 'partenaire_id'],
                'compte_membre' => ['id', 'nom', 'prenom'],
                'remise_obtenus' => ['id', 'offre_id', 'compte_membre_id', 'date_benefice']
            ],
            [
                'offre' => 'offre.id = remise_obtenus.offre_id',
                'compte_membre' => 'compte_membre.id = remise_obtenus.compte_membre_id'
            ],
            [
                'type' => 'INNER',
                'where' => ['offre.partenaire_id' => $id]
            ]
        );
    }

    public function getDiscountById($id){
        return $this->where(['id' => $id]);
    }

    public function getDiscountsByOfferId($id){
        return $this->where(['offre_id' => $id]);
    }

    public function getMemberDiscounts($membre_id) {
        return $this->join(
            [
                'offre' => ['id', 'type_offre', 'valeur', 'partenaire_id'],
                'partenaire' => ['id', 'nom']
            ],
            [
                'offre' => 'offre.id = remise_obtenus.offre_id',
                'partenaire' => 'partenaire.id = offre.partenaire_id'
            ],
            [
                'type' => 'INNER',
                'order_column' => 'remise_obtenus.date_benefice',
                'order_type' => 'DESC',
                'where' => ['remise_obtenus.compte_membre_id' => $membre_id]
            ]
        );
    }
}