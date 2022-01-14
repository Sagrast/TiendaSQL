<!DOCTYPE html>
<?php
//Autor: Oscar González Martínez
//Versión: 1.0
//Fecha: 3/11/21
//Proyecto Tienda: Indice, carrito.
include_once "./class/DAO.php";
include_once "./class/Produtos.class.php";
include_once "./class/Cesta.class.php";
session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Benvidos a CPC Store</title>
        <?php
        //Configuración del estilo de la página
        if (isset($_COOKIE['estilo'])) {
            echo '<link rel="styleSheet" href="' . $_COOKIE['estilo'] . '"/>';
        } else {
            echo '<link rel="stylesheet" href="./estilos/style.css"/>';            
        }
        ?>
    </head>
    <body>        
        <?php
        //Sesiones
        if (isset($_SESSION['userSesion'])) {
            include "menu.php";

            if (isset($_SESSION['cesta'])) {
                echo '<a href="cesta.php">Cesta de ' . $_SESSION['userSesion'] . '</a> ';
                $cesta = $_SESSION['cesta'];
            } else {
                $cesta = new Cesta();
                $_SESSION['cesta'] = $cesta;
            }
        } else {
            include "header.php";
            ?>
            <br/>
            <div style="align:center">            
                <button><a href="./login.php">Iniciar sesión</a></button>
            </div>
            <br/>
            <?php
        }

        $codigo = "";
        if (isset($_POST['engadir'])) {
            $codigo = $_POST['codigo'];
            if (DAO::buscarProducto($codigo) == false) {
                $cesta->engadirArtigo($codigo);
            }
            $_SESSION['cesta'] = $cesta;
            echo "<br>+ Artigo engadido ... <br>";
        }


        //Almacenamos en una variable el array del inventario de la tienda.        
        $stock = DAO::leerProdutosBDD();
        $error = "";
        var_dump($stock);

        //Mostramos la tabla de contenidos de la tienda.
        ?>
        <table class="lista">

            <tr>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Descripcion</th>
                <th>Unidades</th>
                <th>Prezo</th>
                <th>IVE</th>
                <th>Mercar</th>
            </tr>
<?php
foreach ($stock as $productos) {
    ?>
                <tr>
                <form method ="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                    <td><img class="imgStore" src="<?php echo $productos['fotos'] ?>"/></td>
                    <td><input type="text" value="<?php echo $productos['nome']; ?>"disabled/></td>
                    <td><input type="text" value="<?php echo $productos['descricion']; ?>" disabled/></td>
                    <td><input type="text" value="<?php echo $productos['unidades']; ?>" disabled /></td>
                    <td><input type="text" value="<?php echo $productos['prezo']; ?>" disabled/></td>
                    <td><input type="text" value="<?php echo $productos['ive']; ?>" disabled/></td>                    
                    <input type="hidden" value="<?php echo $productos['codigoProd']; ?>" name="codigo"/>
    <?php
    if (isset($_SESSION['userSesion'])) {
        echo '<td><input type="submit" name="engadir" value="engadir"></td>';
    } else {
        echo '<td><a href="rexistro.php">Mercar</a></td>';
    }
    ?>
                    </tr>
                </form>     
    <?php
}
?>

        </table>

    </body>
</html>
