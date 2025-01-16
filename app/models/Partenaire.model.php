<?php

class PartenaireModel{
    use Model;

    protected $table = "Partenaire";
    protected $allowedColumns = ['nom', 'ville', 'adresse', 'numero_de_telephone', 'email', 'site_web', 'logo','categorie_id','statut'];

    public function getAllPartenaires($limit = 10, $offset = 0){
        return $this->findAll($limit, $offset);
    }

    public function getAllPartners(){
        return $this->findAll();
    }


    public function getPartnerInfosWithCategory($id) {
        return $this->join(
            ['categorie' => ['id','nom']],
            ['categorie' => 'partenaire.categorie_id = categorie.id'],
            [
                'type' => 'LEFT',
                'order_column' => 'partenaire.id',
                'where' => ['partenaire.id' => $id],
            ]
        );
    }

    public function getPartenaireById($id){
        return $this->where(['id' => $id]);
    }

    public function getPartenaireByVille($ville){
        return $this->where(['ville' => $ville]);
    }

    public function getPartenaireByCategorie($categorie_id){
        return $this->where(['categorie_id' => $categorie_id]);
    }

    public function getPartenaireByStatut($statut){
        return $this->where(['statut' => $statut]);
    }

    public function getPartenaireByNom($nom){
        return $this->where(['nom' => $nom]);
    }

    public function getPartnerWithSearch($search, $exact_match , $limit = 10, $offset = 0) {
        return $this->search($search, $exact_match, $limit, $offset);
    }

    public function getTotalPartenaires($conditions=[]){
        return $this->getTotalCount($conditions);
    }

    public function getFilteredPartners($filters, $limit = 10, $offset = 0) {
        $orderBy = match($filters['sort'] ?? 'nom_asc') {
            'nom_desc' => 'nom DESC',
            'ville_asc' => 'ville ASC',
            'ville_desc' => 'ville DESC',
            default => 'nom ASC'
        };

        return $this->search(
            [],
            [],
            $limit,
            $offset,
            $orderBy
        );
    }

}