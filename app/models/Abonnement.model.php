<?php
class AbonnementModel {
    use Model;
    
    protected $table = "Abonnement";
    protected $allowedColumns = [
        'type_abonnement',
        'date_debut',
        'date_fin',
        'recu_paiement',
        'statut',
        'is_active'
    ];

    public function getAllSubscriptions() {
        return $this->findAll();
    }

    public function getSubscriptionById($id) {
        return $this->where(['id' => $id]);
    }

    public function getSubscriptionByType($type) {
        return $this->where(['type_abonnement' => $type]);
    }

    public function getSubscriptionByStatus($status) {
        return $this->where(['statut' => $status]);
    }
    
}