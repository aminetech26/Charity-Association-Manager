<?php
class AbonnementModel {
    use Model;
    
    protected $table = "Abonnement";
    protected $allowedColumns = [
        'type_abonnement',
        'date_debut',
        'date_fin',
        'recu_paiement',
        'statut'
    ];
    
    
}