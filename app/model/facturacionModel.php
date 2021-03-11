<?php

class facturacionModel{

    private $db;

    function __construct(){

        $this->db = new PDO(/*CONEXION CON BASE DE DATOS*/);
    }

    function insertFactura($dia, $mes, $anio, $montoTotal, $id_comanda) {

        $query = $this->db->prepare('INSERT INTO facturacion(dia, mes, montoTotal, id_comanda) VALUES(?,?,?,?)');
        $query->execute([$dia, $mes, $anio, $montoTotal, $id_comanda]);
    }
}