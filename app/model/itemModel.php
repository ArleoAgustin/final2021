<?php

class itemModel{

    private $db;

    function __construct(){

        $this->db = new PDO(/*CONEXION CON BASE DE DATOS*/);
    }

    function getItemByComanda($id_comanda){

        $query = $this->prepare("SELECT * FROM item WHERE id_comanda=?");
        $query->execute([$id_comanda]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
}