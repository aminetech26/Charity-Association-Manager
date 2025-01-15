<?php
class DemandeAideModel{
    use Model;
    protected $table = 'demande_aide';
    protected $allowedColumns = ['id','nom','prenom','date_naissance','type_aide','description','fichier_zip','statut','created_at','membre_id'];

    public function getAllDemandeAides($limit = 10,$offset = 0){
        return $this->findAll($limit,$offset);
    }

    public function getAllDemandeAideWithTypeAide($limit = 10,$offset = 0){
        return $this->join(
            ['type_aide' => ['id', 'label']],
            ['type_aide' => 'type_aide.id = demande_aide.type_aide'],
            [
                'type' => 'LEFT',
                'limit' => $limit,
                'offset' => $offset,
                'order_column' => 'demande_aide.created_at',
                'order_type' => 'ASC',
                'where' => ['demande_aide.statut' => 'en attente']
            ]
        );
    }

    public function fetchAll(){
        return $this->findAll();
    }

    public function getDemandeAideById($id){
        return $this->where(['id' => $id]);
    }

    public function getTotalDemandeAide(){
        return $this->getTotalCount();
    }

    public function createDemandeAide($data){
        return $this->create($data);
    }

    public function updateDemandeAide($data,$id){
        return $this->update($data,$id);
    }

    public function deleteDemandeAide($id){
        return $this->delete($id);
    }

    public function getMemberAssistanceRequests($membre_id) {
        return $this->join(
            ['type_aide' => ['id', 'label']],
            ['type_aide' => 'type_aide.id = demande_aide.type_aide'],
            [
                'type' => 'INNER',
                'order_column' => 'demande_aide.created_at',
                'order_type' => 'DESC',
                'where' => ['demande_aide.membre_id' => $membre_id]
            ]
        );
    }
}