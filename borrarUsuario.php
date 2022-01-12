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
        //Carga del CSV en un array
        $usuarios = DAO::leerUsuarios("usuarios.csv");
        //Variable que contendrá la ID a buscar.
        $id = "";
        $adminError = "No eres Administrador";

        //Comprobación de que recibe por el metodo $_GET la ID del usuario a eliminar.
        if (isset($_GET)) {
            $id = $_GET['id'];
        }

        //Comprobamos que el usuario sea administrador para poder borrar.
        if (DAO::esAdmin($_SESSION['userSesion'], $usuarios)) {
            //En un nuevo array almacenamos la salida del metodo Borrar usuario.
            $borrado = DAO::borrarUsuario($id, $usuarios);
            //Ordenamos el array para eliminar campos vacíos.
            $ordenado = array_values($borrado);
            //Llamamos al metodo escribir CSV, le pasamos el nombre del archivo y el array que ha de escribir.
            DAO::escribirUsuarios("usuarios.csv", $ordenado);
            header("Location: usuarios.php");
        } else {
            die("Non tes permisos de Administrador. Debe <a href='login.php'>identificarse</a>.<br/>");
        }
        ?>
    </body>
</html>
