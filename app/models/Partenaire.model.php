<?php

class PartenaireModel{
    use Model;

    protected $table = "Partenaire";
    protected $allowedColumns = ['nom', 'ville', 'adresse', 'numero_de_telephone', 'email', 'site_web', 'logo','categorie_id','statut'];

    public function getAllPartenaires($limit = 10, $offset = 0){
        return $this->findAll($limit, $offset);
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

    public function getTotalPartenaires() {
        return $this->getTotalCount();
    }

}