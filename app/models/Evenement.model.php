<?php 
class EvenementModel{
    use Model;
    protected $table = 'Evenement';
    protected $allowedColumns = ['titre','description','lieu','date_debut', 'date_fin'];

    public function getAllEvenements(){
        return $this->findAll();
    }

    public function getEvenementById($id){
        return $this->where(['id' => $id]);
    }
}