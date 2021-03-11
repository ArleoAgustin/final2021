<?php

class userController {

    private $model;

    function __construct(){

        $this->model = new modelUser();
    }

    function estaLogin(){
        
        $login = false;
        if (isset($_SESSION["user"])) {
            $login = true;
        }
        else {
            header("Location: ".BASE_URL."login");
        }
        return $login;
    }
}