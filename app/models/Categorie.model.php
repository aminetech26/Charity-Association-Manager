<?php
class CategorieModel{
    use Model;

    protected $table = "Categorie";
    protected $allowedColumns = ['nom'];

    public function getAllCategories(){
        return $this->findAll();
    }

    public function getCategorieById($id){
        return $this->where(['id' => $id]);
    }

    public function getCategorieByNom($nom){
        return $this->where(['nom' => $nom]);
    }
}