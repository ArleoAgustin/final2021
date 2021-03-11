<?php

require_once("./app/model/comandaModel.php");
require_once("./app/model/facturacionModel.php");
require_once("./app/model/itemModel.php");
require_once("userController.php");

class comandaController {

    private $comandaModel;
    private $userController;
    private $comandaView;
    private $facturacionModel;
    private $itemModel;

    function __construct(){

        $this->comandaModel = new comandaModel();
        $this->userController = new userController();
        $this->comandaView = new comandaView();
        $this->facturacionModel = new facturacionModel();
        $this->itemModel = new itemModel();
    }

//EJERCICIO 1 A
    function insertComanda(){

        if ((isset($_POST["nro_comanda"])) && (!empty($_POST["nro_comanda"])) &&
        (isset($_POST["nro_mesa"])) && (!empty($_POST["nro_mesa"])) &&
        (isset($_POST["cerrada"])) && (!empty($_POST["cerrada"]))) {

                $nro_comanda = $_POST["nro_comanda"];
                $nro_mesa = $_POST["nro_mesa"];
                $cerrada = $_POST["cerrada"];

                $login = $this->userController->login();

                if ($login = true) {

                    $comandas = $this->comandaModel->getComanda($nro_mesa); //traigo las comandas de la mesa

                    if(empty($comandas)) {

                        $comanda_abierta = false;
                        foreach ($comandas as $comanda) {
                           
                            if ($comanda->cerrada == 0) {   //si hay una comanda abierta
                                $comanda_abierta = true;
                            }
                        }

                        if($comanda_abierta = false){ //si no hay otra comanda abierta 

                            $this->comandaModel->insertComanda($nro_comanda, $nro_mesa, $cerrada);
                        }
                        else{
                            $this->comandaView->showMessage("Existe otra comanda en la mesa");
                        }

                    }
                    else {
                        $this->comandaView->showMessage("No existen comandas");
                    }

                }
                else{
                    $this->comandaView->showMessage("Inicie sesion para realizar esta accion");
                }
        }
        else{
            $this->comandaView->showMessage("Ingrese todos los datos correctamente");
        }
    }

//EJERCICIO 2

    function cerrarComanda(){

        if ((isset($_POST["nro_comanda"])) && (!empty($_POST["nro_comanda"])) &&
        (isset($_POST["nro_mesa"])) && (!empty($_POST["nro_mesa"])) &&
        (isset($_POST["cerrada"])) && (!empty($_POST["cerrada"]))) {

            $nro_mesa = $_POST["nro_mesa"];
            $nro_comanda = $_POST["nro_comanda"];
            $cerrada = $_POST["cerrada"]; 

            $login = $this->userController->login();

            if ($login = true) {

                $this->comandaModel->cerrarComanda($nro_mesa, $nro_comanda, $cerrada);
                $comanda = $this->comandaModel->getComandasByNro($nro_comanda);
                
                if(!empty($comanda)){
                    $items = $this->itemModel->getItemByComanda($comanda->id);
                    

                    if(!empty($items)){
                        $montoTotal = 0;
                        
                        foreach ($items as $item) {
                        
                            $montoTotal+= $item->precio_unitario;
                        }

                        if ($montoTotal > 1000){

                            $descuento = ($montoTotal * 10)/100
                            $montoTotal - $descuento;
                        }

                        if ((isset($_POST["dia"])) && (!empty($_POST["dia"])) &&
                        (isset($_POST["mes"])) && (!empty($_POST["mes"])) &&
                        (isset($_POST["anio"])) && (!empty($_POST["anio"]))) {

                            $dia = $_POST["dia"];
                            $mes = $_POST["mes"];
                            $anio = $_POST["anio"];

                            $this->facturacionModel->insertFactura($dia, $mes, $anio, $montoTotal, $comanda->id);
                        }
                        else{
                            $this->comandaView->showMessage("Ingrese los datos");
                        }
                            
                    }
                }
            }
            else{
                $this->comandaView->showMessage("Por favor inicie sesion");
            }
        }
        else{
            $this->comandaView->showMessage("Ingrese los datos");
        }
    }  

//EJERCICIO 3 A

    function generarTablaDeComandas(){

        $comandas = $this->comandasModel->getAllComandas();
        $arrComandas = [];  //arreglo para contener todas las comandas

        if (!empty($comandas)){

            
            foreach ($comandas as $comanda) {
            
                if ($comanda->cerrada == 0){ //si la comanda esta abierta

                    $dataComandas = new stdClass(); //creo un objeto para guardas las comandas
                    $dataComandas->nro_mesa = $comanda->nro_mesa;  
                    $items = $this->itemModel->getItemByComanda($comanda->id);   //obtengo los items de la comanda
                    $arrItems = []; //arreglo para contener los items de la comanda

                    if (!empty($items)){

                        $montoTotal= 0;
                        foreach ($items as $item) {
                            
                            $dataItem = new stdClass(); //objeto para almacenar los datos del item
                            $dataItem->nombre_item = $item->nombre_item;
                            $dataItem->cantidad = $item->cantidad;
                            $dataItem->precio = $item->precio_unitario;
                            $jsonItem = json_encode($dataItem); //lo convierto en json
                            array_push($arrItems, $jsonItem);   //se agrega al arreglo

                            $montoTotal+= $item->precio_unitario;   //voy sumando el monto de cada item
                        }
                    }
                    $dataComandas->precio_total = $montoTotal;
                    $dataComandas->items = $arrItems; //agrego el arreglo que contiene los items al objeto de comandas
                    $jsonComanda = json_encode($dataComandas);
                    array_push($arrComandas, $jsonComanda);
                }
                
            }
            else{
                $this->comandaView->$this->comandaView->showMessage("No hay comandas disponibles");
            }
    }
}