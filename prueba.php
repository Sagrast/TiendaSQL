<?php
include_once "./class/DAO.php";
include_once "./class/Produtos.class.php";
  DAO::buscarUserBDD(1);
  var_dump(DAO::findProductsBDD(2));

  $prueba = new Produtos("Probando","DIE DIE DIE!","10","100","/var/fotos","21");
  $prueba->setCodigo(3);
  $codigo = $prueba->getCodigo();
  var_dump($prueba);
  DAO::updateProdBDD($prueba,$codigo);
  
  
?>