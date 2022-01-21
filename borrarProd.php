<!DOCTYPE html>
<?php
//Autor: Oscar González Martínez
//Versión 1.0
//Fecha: 30/10/21
//Proyecto Tienda: Borrar Usuario

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
        <title>Borrar Usuarios</title>
    </head>
    <body>
        <?php
        
        //Con el traslado a BDD ya no necesitamos cargar en memoria todos los datos.
        //$usuarios = DAO::userBDD();
        //Variable que contendrá la ID a buscar.
        $id = "";
        $adminError = "No eres Administrador";

        //Comprobación de que recibe por el metodo $_GET la ID del usuario a eliminar.
        if (isset($_GET)) {
            $id = $_GET['id'];
        }

        //Comprobamos que el usuario sea administrador para poder borrar.
        if (DAO::esAdminBDD($_SESSION['userSesion'])) {           
            DAO::deleteProdBDD($id);            
            header("Location: productos.php");
        } else {
            die("Non tes permisos de Administrador. Debe <a href='login.php'>identificarse</a>.<br/>");
        }
        ?>
    </body>
</html>
