<?php 
class EvenementModel{
    use Model;
    protected $table = 'evenement';
    protected $allowedColumns = ['id','titre','description','lieu','date_debut', 'date_fin'];

    public function getAllEvenements($limit = 10, $offset = 0){
        return $this->findAll($limit, $offset);
    }

    public function getEvenementById($id){
        return $this->where(['id' => $id]);
    }

    public function getTotalEvents(){
        return $this->getTotalCount();
    }
}