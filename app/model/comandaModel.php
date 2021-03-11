<?php

class comandaModel{

    private $db;

    function __construct(){

        $this->db = new PDO(/*CONEXION CON BASE DE DATOS*/);
    }


    function insertComanda($nro_comanda, $nro_mesa, $cerrada){

        $query = $this->db->prepare('INSERT INTO comanda(nro_comanda, nro_mesa, cerrada) VALUES(?,?,?)');
        $query->execute([$nro_comanda, $nro_mesa, $cerrada]);

    }

    function getComanda($nro_mesa) {

        $query = $this->prepare("SELECT * FROM comanda WHERE nro_mesa=?");
        $query->execute([$nro_mesa]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    function cerrarComanda($nro_mesa, $nro_comanda, $cerrada){

        $query = $this->db->prepare("UPDATE comanda SET cerrada=? WHERE nro_mesa=? AND nro_comanda=?");
        $query->execute([$cerrada, $nro_mesa, $nro_comanda]);
    }

    function getComandasByNro($nro_comanda){
        
        $query = $this->prepare("SELECT * FROM comanda WHERE nro_comanda=?");
        $query->execute([$nro_comanda]);
        return $query->fetch(PDO::FETCH_OBJ);
    }


}