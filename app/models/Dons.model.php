<?php
class DonsModel{
    use Model;
    protected $table = 'dons';
    protected $allowedColumns = ['id','montant', 'compte_membre_id', 'date', 'est_tracable','statut','recu_paiement'];

    public function getAllDons(){
        return $this->findAll();
    }

    public function getAllDonsWithMemberUniqueIds($limit = 10, $offset = 0){
        return $this->join(
            ['compte_membre' => ['id', 'nom','prenom']],
            ['compte_membre' => 'compte_membre.id = dons.compte_membre_id'],
            [
                'type' => 'LEFT',
                'limit' => $limit,
                'offset' => $offset,
                'order_column' => 'dons.date',
                'order_type' => 'DESC',
                'where' => ['dons.statut' => 'EN_ATTENTE']
            ]
        );
    }

    public function getDonById($id){
        return $this->find(['id' => $id]);
    }

    public function getDonByMembreId($membre_id){
        return $this->find(['compte_membre_id' => $membre_id]);
    }

    public function getTotalDons(){
        return $this->getJoinTotalCount(
            ['compte_membre' => ['id', 'nom','prenom']],
            ['compte_membre' => 'compte_membre.id = dons.compte_membre_id'],
            [
                'where' => ['dons.statut' => 'EN_ATTENTE']
            ]
        );
    }

}