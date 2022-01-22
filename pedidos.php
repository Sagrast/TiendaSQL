<?php
//Autor: Oscar Gonzále Martínez
//Paxina de Visualización de pedidos.
//Data: 20/01/2022

include "./class/DAO.php";
include_once './class/Users.class.php';
session_start();

if (!isset($_SESSION['userSesion'])) {
    die("Erro - debe <a href='login.php'>identificarse</a>.<br/>");
}
?>

<!DOCTYPE html>
<html lang="en">
<?php
include "./recursos/head.php";
?>
<?php
if (!isset($_SESSION['userSesion'])) {
    die(header("location: login.php"));
} else if (!DAO::esAdminBDD($_SESSION['userSesion'])) {
    //Si la función esAdmin() devuelve falso, el usuario no podrá entrar en la página.
    die("Non es administrador. <a href='login.php'>identificarse</a>.<br/>");
} else {
    //Incluimos menu.php solo si la sesión está iniciada.
    include 'menu.php';
}

$pedidos = DAO::pedidosBDD();
$cont = 1;

?>
<body>
<table class="table table-striped table-dark">
    <tr>
        <th>Usuario</th>
        <th>Producto</th>
        <th>Prezo</th>        
        <th>Cantidad</th>        
        <th>Prezo Total</th>
        <th>Operacions</th>        
    </tr>
    <?php
    foreach ($pedidos as $p) {
        ?>
            <tr>
                <td><?php echo $p->userLogin ?></td>
                <td><?php echo $p->nome ?></td>
                <td><?php echo $p->prezo ?></td>
                <td><?php echo $p->cantidad ?></td>
                <td><?php echo $p->precio_total ?></td>
                <td><a href="borrarPedidos.php?id=<?php echo $p->codigoPedidos; ?>">Eliminar </a> /
                <a href="updatePedidos.php?id=<?php echo $p->codigoPedidos; ?>"> Modificar</a></td>
            </tr>
        <?php
        }
        ?>
</table>

</body>
</html>