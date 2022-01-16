<?php 
/* 
    Conexión a base de datos.
    Autor: Oscar González Martínez
    Fecha: 12/01/2022
*/

     class Connect {

        //Declaración de atributos de la clase. Configurados con los datos de conexión al servidor Web
        private $hostname = 'localhost';
        private $login = 'phpmyadmin';
        private $pass = 'abc123.';
        private $dataBase = 'tienda';
        private $conect;
        
    
    //función de conexión
     public function conexion(){
         //Cadena con los datos que recibe la clase PDO para realizar una conexión: Dirección de host y BDD contra la que conectar. Como opcion el formato de caracteres.
        $conectionString = "mysql:host=$this->hostname;dbname=$this->dataBase;charset=utf8";        
        try {
            //En un bloque Try/Cactch creamos un nuevo objeto PDO con la cadena previamente creada, login y contraseña.
            $this->conect = new PDO($conectionString,$this->login,$this->pass);
            //Le establecemos los atributos de control de errores y control de excepciones para tener un código menos redundante. 
            $this->conect ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            return $this->conect;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
     }

     }
