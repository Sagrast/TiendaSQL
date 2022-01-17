<!DOCTYPE html>
<?php
//@author: Oscar González Martínez
//@Version: 1.0
//Fecha: 08/11/21
include "./class/DAO.php";
include_once "./class/Produtos.class.php";
include_once "./class/Users.class.php";
include_once "./class/Cesta.class.php";
session_start();

if (isset($_SESSION['userSesion'])) {
    include "menu.php";
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Cesta da compra</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <?php
        //Si hay una cookie tipo estilo establecida
        if (isset($_COOKIE['estilo'])) {
            //toma su valor y define el archivo de stilo cargado en la página.
            echo '<link rel="styleSheet" href="' . $_COOKIE['estilo'] . '"/>';
        } else {
            //Si no está establecida dejamos por defecto el tema claro.
            echo '<link rel="stylesheet" href="./estilos/style.css"/>';
        }
        ?>
    </head>
    <body>
        <?php
        if (isset($_SESSION['cesta'])) {
            //Si hay una session Cesta.
            $cesta = $_SESSION['cesta'];
            //Leemos CSV de productos
            $productos = DAO::productsBDD();
            $usuarios = DAO::userBDD();
            //almacenamos en un array los indices del producto.
            $cestaKeys = array_keys($cesta->getProducto());
            //Almacenamos el usuario de la sesion en una variable.
            $username = $_SESSION['userSesion'];            
            //inicializamos una variable importe acumular la suma del precio de los productos.
            $importe = 0;
            //Iniciamos un bucle ForEach que recorre los objetos de productos. Y creamos la tabla ce la cesta.
            $compra = array();            
            
            /*
              --------------------------------------------- CODIGO FORMULARIO ----------------------------------------------

             */  
            ?>            
                <table class="lista">                
                    <tr>                
                        <th>Nombre</th>
                        <th>Descripcion</th>                
                        <th>Prezo</th>
                        <th>Unidades</th>
                        <th>Importe</th>
                        <th>Eliminar</th>
                    </tr>
                    <?php
                    $contCesta = 0;
                    foreach ($productos as $stock) {
                        //Si el codigo del producto esta en el array de la cesta
                        //mostramos una tabla con los datos de dicho producto.
                        if (in_array($stock->getCodigo(), $cestaKeys)) {
                            echo "<tr>";
                            echo '<form method ="post" action="'.$_SERVER['PHP_SELF'].'">';
                            echo '<input type="hidden" name="codigo" value="'.$stock->getCodigo().'"/>';
                            echo '<td><input type="text name="nome" value="' . $stock->getNome() . '" readonly></td>';
                            echo '<td><input type="text name="descricion" value="' . $stock->getDescricion() . '" readonly></td>';
                            echo '<td><input type="text name="prezoUnidad" value="' . $stock->getPrezo() . '" readonly></td>';
                            echo '<td><input type="text name="unidades" value="' . $cesta->getProducto()[$stock->getCodigo()] . '" readonly></td>';
                            //Calculamos el precio total: precio del producto por numero de objetos en la cesta.
                            $precio = $stock->getPrezo() * $cesta->getProducto()[$stock->getCodigo()];
                            echo '<td><input type="text name="prezoTotal" value="' . $precio . '" readonly></td>';
                            echo '<td><input type="submit" name="eliminar" value="eliminar"/></td>';                            
                            echo "</tr>";
                            echo "</form>";
                            //Acumulamos en cada vuelta el precio al importe
                            $importe += $precio;
                            if (isset($_POST['eliminar'])){
                               $cesta->eliminarArtigo($stock->getCodigo());
                               //echo "<meta http-equiv='refresh' content='0'>";
                            }                            
                        }
                    }                    
                    ?>            
        </table>
        <br/>          
        <table class="lista">
            <tr>                
                <th>Titular</th>
                <th>Enderezo</th>                
                <th>Email</th>
                <th>Total a Pagar</th>

            </tr>
    <?php
    //Iniciamos un bucle recorriendo el array de usuarios.
    $codigo = "";
    foreach ($usuarios as $users) {
        //Si el login del usuario coincide con el que ha iniciado sesion
        //Mostramos sus datos por pantalla.
        if (strcasecmp($users->getLogin(), $username) == 0) {

            echo "<tr>";
            echo '<td>' . $users->getNome() . "</td>";
            echo '<td>' . $users->getEnderezo() . "</td>";
            echo '<td>' . $users->getEmail() . "</td>";
            //Mostramos el valor acumulado en importe, como precio final.
            echo "<td>" . $importe . " </td>";
            echo "</tr>";
            $codigo = $users->getCodigo();
        }
    }
    
    if (isset($_POST['pagar'])){
        DAO::escribirCestaBDD($cestaKeys,$codigo,$importe);
        echo "<h3>Compra Realizada con éxito</h3>";
    } else if(isset($_POST['seguir'])){
        header("Location: index.php");
    }
    
    if (!empty($cesta)){
    echo '<form action="'.$_SERVER['PHP_SELF'].'" method="post">';
    echo '<input type="submit" value="Pagar" name="pagar"/>';
    echo '<input type="submit" value="Seguir Mercando" name="seguir"/>';
    echo '</form>';
    }    
} else {
    echo '<h3> A cesta está baleira, <a href="index.php">prema para voltar a tenda</a></h3>';
}
?>
    </table>
</body>
</html>
