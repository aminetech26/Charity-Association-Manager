<?php
//defined('ROOTPATH') OR exit('Access Denied!');

class MembreModel{
    use Model;
    protected $table = "Compte_Membre";
    protected $allowedColumns = ['nom', 'prenom', 'email', 'mot_de_passe', 'photo', 'piece_identite', 'adresse', 'numero_de_telephone'];





}