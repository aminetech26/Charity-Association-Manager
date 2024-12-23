<?php

class PartenaireFavoriModel{
    use Model;
    protected $table = 'Partenaire_Favoris';
    protected $allowedColumns = ['compte_membre_id','partenaire_id'];

    public function getPartenaireFavorisByPartenaire($id_partenaire){
        return $this->where(['partenaire_id' => $id_partenaire]);
    }

    public function getPartenaireFavorisByUtilisateur($id_utilisateur){
        return $this->where(['compte_membre_id' => $id_utilisateur]);
    }
}