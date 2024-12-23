<?php

class NotificationModel{
    use Model;
    protected $table = 'Notification';
    protected $allowedColumns = ['titre','contenu','date_envoi', 'is_sent','groupe_cible','type','created_by'];

    public function getAllNotifications(){
        return $this->findAll();
    }

    public function getNotificationById($id){
        return $this->where(['id' => $id]);
    }

    public function getNotificationByGroupeCible($groupe_cible){
        return $this->where(['groupe_cible' => $groupe_cible]);
    }

    public function getNotificationByType($type){
        return $this->where(['type' => $type]);
    }
}