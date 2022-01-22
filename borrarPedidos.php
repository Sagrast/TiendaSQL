<!DOCTYPE html>
<?php
//Autor: Oscar González Martínez
//Versión 1.0
//Fecha: 22/01/2022
//Proyecto Tienda: Borrar Pedidos

include "./class/DAO.php";
include_once './class/Users.class.php';
session_start();

if (!isset($_SESSION['userSesion'])) {
    die("Erro - debe <a href='login.php'>identificarse</a>.<br/>");
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Borrar Pedidos</title>
    </head>
    <body>
        <?php        
        
        //Comprobación de que recibe por el metodo $_GET la ID del usuario a eliminar.
        if (isset($_GET)) {
            $id = $_GET['id'];
        }

        //Comprobamos que el usuario sea administrador para poder borrar.
        if (DAO::esAdminBDD($_SESSION['userSesion'])) {           
            DAO::borrarPedidoBDD($id);
            header("Location: pedidos.php");
        } else {
            die("Non tes permisos de Administrador. Debe <a href='login.php'>identificarse</a>.<br/>");
        }
        ?>
    </body>
</html>
