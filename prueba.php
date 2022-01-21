<?php
include_once "./class/DAO.php";
  DAO::buscarUserBDD(1);
  $usuario = new Users("Administrador","Larry","una","Prueba","Calle","un@correo.es");
  DAO::updateUserBDD($usuario,4);
?>