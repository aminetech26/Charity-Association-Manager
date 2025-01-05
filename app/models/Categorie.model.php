<?php
class CategorieModel{
    use Model;

    protected $table = "Categorie";
    protected $allowedColumns = ['nom'];

    public function getAllCategories($limit = 10, $offset = 0){
        return $this->findAll($limit,$offset);
    }

    public function getCategorieById($id){
        return $this->where(['id' => $id]);
    }

    public function getCategorieByNom($nom){
        return $this->where(['nom' => $nom]);
    }

    public function getTotalCategories(){
        return $this->getTotalCount();
    }
}