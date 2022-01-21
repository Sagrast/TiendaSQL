<?php
include_once "./class/DAO.php";
include_once "./class/Produtos.class.php";
  DAO::buscarUserBDD(1);
  var_dump(DAO::findProductsBDD(2));

  $prueba = new Produtos("Probando","Que Funciona El Insert","10","100","/var/fotos","21");
  $prueba->setCodigo(3);
  var_dump($prueba);
  DAO::updateProdBDD($prueba,3);
  
  
?>