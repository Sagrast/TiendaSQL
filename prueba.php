<?php
    include "./class/DAO.php";
    include_once "./class/Users.class.php";

    //var_dump(DAO::userBDD());

    $cesta = array(1,200);
    $user = new Users("admin","prueasba","fkndksnfs","cesta","calle","un@correo");
    $user->setCodigo(1);

    DAO::escribirCestaBDD($cesta,$user,1000);

    

?>