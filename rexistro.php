<?php
/*
 * @author: Oscar González Martínez
 * @version: 1.0
 * Fecha: 06/11/2021
 */

include "./class/DAO.php";
include "./functions/form.php";
include_once "./class/Users.class.php";
//$database = DAO::leerUsuarios("usuarios.csv");
?>
<!DOCTYPE html>
<html lang="en">
<?php
include "./recursos/head.php";
?>
<?php
//Declaracion de variables de errores.
$loginError = "";
$emailError = "";
$contrasinalError = "";
$validarError = "";
$nomeCompletoError = "";
$enderezoError = "";
//Array de Errores.
$erros = array();
//Comprobaciones propias del formulario
if (isset($_POST['gardar'])) {
    //Comprobamos que todos los campos tengan valor antes de llamar a la funcion 
    //De insercion en el CSV.
    if (isset($_POST['login'])) {
        $login = $_POST['login'];
    } else {
        $loginError = "O campo está vacío";
        array_push($erros, $loginError);
    }

    //Correo electronico.
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
    } else {
        $emailError = "Introduza unha dirección de correo";
        array_push($erros, $emailError);
    }

    //Validar 
    //Añadimos en una variable el campo escrito en validar.
    if (isset($_POST['validar'])) {
        $validar = $_POST['validar'];
    } else {
        //Si está vacío generamos un error y lo añadimos al array.
        $validarError = "Debe verificar o contrasinal";
        array_push($erros, $validarError);
    }

    //Contrasinal 
    if (isset($_POST['pass'])) {
        //Si está cubierto el campo password lo comparamos con el almacenado en validar.
        if (strcmp($_POST['pass'], $validar) === 0) {
            //Si los valores son iguales, hacemos la verificación de la contraseña mediante la función creada
            $contrasinal = $_POST['pass'];
        } else {
            //En caso contrario generamos los errores correspondientes y los almacenamos en el array.
            $contrasinalError = "As contrasinais non son iguais";
            array_push($erros, $contrasinalError);
        }
    } else {
        $contrasinalError = $validarError = "Introduzca Contrasinal";
        array_push($erros, $validarError);
    }

    //Nome completo.
    if (isset($_POST['fullName'])) {
        $nomeCompleto = $_POST['fullName'];
    } else {
        $nomeCompletoError = "Introduzca Nome";
        array_push($erros, $nomeCompletoError);
    }

    //Direccion.
    if (isset($_POST['address'])) {
        $enderezo = $_POST['address'];
    } else {
        $enderezoError = "Introduzca direccion";
        array_push($erros, $enderezoError);
    }

    //Si todo está correcto, generamos un nuevo objeto Usuario y lo almacenamos en el CSV.
    if (empty($erros)) {
        form("Usuario", $login, $contrasinal, $nomeCompleto, $enderezo, $email, $database);
    }
}

?>


<body>
    <section class="vh-100 gradient-custom">
        <div class="container register-form">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                <div class="form">
                    <div class="note">
                        <p>Benvidos a RetroTenda. Formulario de Rexistro</p>
                    </div>
                    <div class="form-content">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="fullName" placeholder="Nome" value="<?php if (isset($_POST['fullName'])) {
                                                                                                                            echo $_POST['fullName'];
                                                                                                                        } ?>" />
                                    <?php DAO::erro($nomeCompletoError) ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Login" value="<?php if (isset($_POST['login'])) {
                                                                                                            echo $_POST['login'];
                                                                                                        } ?>" />
                                    <?php DAO::erro($loginError) ?>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="password" class="form-control" name="password" placeholder="Password *" />
                                    <?php DAO::erro($contrasinalError) ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="password" class="form-control" name="validar" placeholder="Confirma Password *" />
                                    <?php DAO::erro($validarError) ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email" placeholder="Correo Electronico" value="" />
                                    <?php DAO::erro($emailError) ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="address" placeholder="Direccion" value="" />
                                    <?php DAO::erro($enderezoError) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 .offset-md-3">
                            <div class="form-group">
                                <button type="submit" name="gardar" class="btnSubmit">Enviar</button>
                            </div>
                        
                            
                        </div>
                    </div>
                </div>
        </div>
        </div>
        </form>
    </section>
</body>