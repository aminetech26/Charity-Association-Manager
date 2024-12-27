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

    public function getPartenaireByNomAndCategorieAndVille($nom = null, $categorie_id = null, $ville = null, $limit = 10, $offset = 0)
    {
        $data = [];
    
        if ($nom !== null && $nom !== '') {
            $data['nom'] = $nom;
        }
        if ($categorie_id !== null && $categorie_id !== '') {
            $data['categorie_id'] = $categorie_id;
        }
        if ($ville !== null && $ville !== '') {
            $data['ville'] = $ville;
        }
    
        if (empty($data)) {
            return $this->findAll($limit, $offset);
        }
    
        return $this->where($data, [], $limit, $offset);
    }

    public function getTotalPartenaires() {
        return $this->getTotalCount();
    }

}