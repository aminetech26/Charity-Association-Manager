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

    public function getMemberVolunteering($membre_id) {
        return $this->join(
            ['evenement' => ['id', 'titre', 'date_debut', 'date_fin']],
            ['evenement' => 'evenement.id = benevolats.evenement_id'],
            [
                'type' => 'INNER',
                'order_column' => 'evenement.date_debut',
                'order_type' => 'DESC',
                'where' => ['benevolats.compte_membre_id' => $membre_id, 'benevolats.statut' => 'EN_ATTENTE']
            ]
        );
    }

    public function getAllWithEventAndMember()
    {
        $sql = "SELECT b.*, cm.nom AS membre_nom, cm.prenom AS membre_prenom, e.titre AS evenement_titre
                FROM benevolats b
                JOIN compte_membre cm ON b.compte_membre_id = cm.id
                JOIN evenement e ON b.evenement_id = e.id
                ORDER BY b.id DESC";
        return $this->query($sql);
    }

    public function approveBenevolat($id)
    {
        return $this->update($id, ['statut' => 'VALIDE']);
    }

    public function refuseBenevolat($id)
    {
        return $this->update($id, ['statut' => 'REFUSE']);
    }

}