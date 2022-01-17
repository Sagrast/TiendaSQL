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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
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
    if (DAO::buscarProductoBDD($codigo) == true) {
        $cesta->engadirArtigo($codigo);
    }
    $_SESSION['cesta'] = $cesta;
    echo "<br>+ Artigo engadido ... <br>";
}

//Almacenamos en una variable el array del inventario de la tienda.
$stock = DAO::productsBDD();
$error = "";

//Mostramos la tabla de contenidos de la tienda.
?>
        <table class="table table-striped table-dark">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Imagen</th>
                <th scope="col">Nombre</th>
                <th scope="col">Descripcion</th>
                <th scope="col">Unidades</th>
                <th scope="col">Prezo</th>
                <th scope="col">IVE</th>
                <th scope="col">Prezo con Ive</th>
                <th scope="col">Mercar</th>
            </tr>
            </thead>
            <tbody>
<?php
foreach ($stock as $productos) {
    ?>
                <tr>


                    <td><img class="imgStore" src="<?php echo $productos->getFotos(); ?>"/></td>
                    <td><?php echo $productos->getNome(); ?></td>
                    <td><?php echo $productos->getDescricion(); ?></td>
                    <td><?php echo $productos->getUnidades(); ?></td>
                    <td><?php echo $productos->getPrezo(); ?></td>
                    <td><?php echo $productos->getIve(); ?> </td>
                    <td><?php echo $productos->prezoConIVE(); ?> </td>
                    <form method ="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                    <input type="hidden" value="<?php echo $productos->getCodigo(); ?>" name="codigo"/>
    <?php
if (isset($_SESSION['userSesion'])) {
        echo '<td><button type="submit" name="engadir">Engadir</td>';
    } else {
        echo '<td><a href="rexistro.php">Mercar</a></td>';
    }
    ?>
                    </tr>
            </tbody>
                </form>
    <?php
}
?>

        </table>

    </body>
</html>
