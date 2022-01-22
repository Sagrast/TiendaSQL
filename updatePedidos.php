<?php
//Autor: Oscar Gonzále Martínez
//Páxina de Actualización de Usuarios.
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
$id = "";
if (!empty($_GET['id']) && isset($_GET['id'])) {
    $id = $_GET['id'];
} else if (isset($_POST['codigoPedido'])) {
    $id = $_POST['codigoPedido'];
}

$datos = DAO::findPedidosBDD($id);
$unidadesErro = "";

if (isset($_POST['engadir'])){
    $codigo = $_POST['codigoPedido'];
    $unidades = $_POST['cantidad'];


    if (!empty($codigo) && !empty($unidades)){
        DAO::updatePedidos($codigo,$unidades);
        header("Location: pedidos.php");
    }
}
?>

<body>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
        <div class="form">
            <div class="note">
                <p>Edición de Pedidos</p>
            </div>
        </div>
        <div class="form-content">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="editNome">Usuario</label>
                        <input type="text" name="usuario" class="form-control" value="<?php echo $datos->userLogin ?>" disabled>                        
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="editDesc">Titulo Mercado</label>
                        <input type="text" class="form-control" name="nomeProd" value="<?php echo $datos->nome ?>" disabled>                        
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="editUnits">Unidades</label>
                        <input type="number" name="cantidad" class="form-control" value="<?php echo $datos->cantidad ?>">
                        <?php DAO::erro($unidadesErro); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="editPrezo">Prezo</label>
                        <input type="number" class="form-control" name="prezo" value="<?php echo $datos->precio_total ?>" disabled>                        
                    </div>
                </div>
                        <input type="hidden" name="codigoPedido" class="form-control" value="<?php echo $datos->codigoPedidos ?>">               
                
                </div>                
                <div class="col">
                    <div class="form-group">
                        <button type="submit" name="engadir" value="engadir" class="btnSubmit">Actualizar</button>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <button type="submit" name="volver" class="btnSubmit">Volver</button>
                    </div>
                </div>                
            </div>
    </form>

  

</body>

</html>