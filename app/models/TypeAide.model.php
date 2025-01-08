<?php
class TypeAideModel{
    use Model;
    protected $table = 'type_aide';
    protected $allowedColumns = ['id','label','description','dossier_requis'];

    public function getAllTypeAides($limit = 10,$offset = 0){
        return $this->findAll($limit,$offset);
    }

    public function getTypeAideById($id){
        return $this->find(['id' => $id]);
    }

    public function getTotalTypeAide(){
        return $this->getTotalCount();
    }
}