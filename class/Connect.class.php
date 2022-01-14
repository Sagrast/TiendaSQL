<?php 
/* 
    Conexión a base de datos.
    Autor: Oscar González Martínez
    Fecha: 12/01/2022
*/

     class Connect {

        private $hostname = 'localhost';
        private $login = 'phpmyadmin';
        private $pass = 'abc123.';
        private $dataBase = 'tienda';
        private $conect;
        
    
     public function conexion(){
        $conectionString = "mysql:host = $this->hostname;dbname = $this->dataBase,charset=utf8";
        try {
            $this ->conect = new PDO($conectionString,$this->login,$this->pass);
            $this->conect ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            return $this->conect;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
     }

     }
