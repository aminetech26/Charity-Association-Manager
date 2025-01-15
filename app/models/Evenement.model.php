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

    public function getEvenementsDisponibles($membre_id) {
        $query = "SELECT e.* 
                 FROM evenement e 
                 WHERE e.date_debut >= CURRENT_DATE()
                 AND NOT EXISTS (
                     SELECT 1 
                     FROM benevolats b 
                     WHERE b.evenement_id = e.id 
                     AND b.compte_membre_id = :membre_id
                 )
                 ORDER BY e.date_debut ASC";

        return $this->query($query, ['membre_id' => $membre_id]);
    }

    public function formatDates($evenements) {
        if ($evenements) {
            foreach ($evenements as &$evenement) {
                $evenement->date_debut = date('d/m/Y', strtotime($evenement->date_debut));
                $evenement->date_fin = date('d/m/Y', strtotime($evenement->date_fin));
            }
        }
        return $evenements;
    }
}