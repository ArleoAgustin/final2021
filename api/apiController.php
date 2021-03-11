<?php

require_once("apiView.php");


class ApiController {

    private $comandaModel;
    private $itemModel;
    private $apiView;
    private $data;

    function __construct(){

        $this->comandaModel = new comandaModel();
        $this->itemModel = new itemModel();
        $this->apiView = new APIView();
        $this->data = file_get_contents('php://input');
    }

    function getData(){
        return json_decode($this->data);    
    }

    function getComanda($params = null) {

        $nro_mesa = $params[":ID"];
        $comanda = $this->comandaModel->getComanda($nro_mesa);
        
        if(!empty($comanda)){
            $this->apiView->response($comanda,200);
        }
        else{
            $this->apiView->response("no hay comandas en la mesa numero $nro_mesa",404);
        }
    }

    function agregarItemEnComanda($params = null){

        $nro_comanda = $params[":ID"]:

        if ((isset($_POST["dia"])) && (!empty($_POST["dia"])) &&
            (isset($_POST["mes"])) && (!empty($_POST["mes"])) &&
            (isset($_POST["anio"])) && (!empty($_POST["anio"]))) {

                $dia = $_POST["dia"];
                $mes = $_POST["mes"];
                $anio = $_POST["anio"];
                $precio_Item = $_POST["precio"];    //se supone q el precio no lo carga el usuario, por eso no verifico que haya ingresado los datos

                $se_agrego = $this->itemModel->insertItem($dia,$mes,$anio,$precio_Item,$nro_comanda); //desde el model retornaria 1 si se pudo completar

                if($se_agrego == 1){

                    $this->apiView->response("Se agrego el item correctamente",200);
                }
                else{
                    $this->apiView->response("No se pudo ingresar el item $nro_mesa",404);
                }
            }
    }

    function actualizarComanda($params=null){
        
        $id_comanda = $params[":ID"];

        if ((isset($_POST["estado"])) && (!empty($_POST["estado"])) {


            $actualizo = $this->itemComanda->actualizarEstado($id_comanda,$_POST["estado"]); 
            
            if($actualizo == 1) {
                $this->apiView->response("Se actualizo el estado correctamente",200);
            }
            else{
                $this->apiView->response("No se pudo actualizar el estado $nro_mesa",404);
            }
        }
    }

    function getComandasByTiempo(){

        $comandas = $this->comandasModel->getComandasByTiempo(); //en el model manejaria lo del tiempo

        if(!empty($comandas)) {

            $this->apiView->response($comandas,200);
        }
        else{
            $this->apiView->response("No hay comandas con mas de 1 hora en preparacion",404);
        }
    }
}