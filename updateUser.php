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

<body>
    <?php
    $id = "";
    $adminError = "No eres Administrador";
    if (!empty($_GET['id']) && isset($_GET['id'])) {
        $id = $_GET['id'];
    } else if (isset($_POST['codigo'])) {
        $id = $_POST['codigo'];
    }
    $usuario = DAO::buscarUserBDD($id);
    $editLoginError = $editContrasinalError = $editValidarError = $editEnderezoError = $editEmailError = $editNomeError = "";
    $rol = $login = $contrasinal = $validaContrasinal = $nomeCompleto = $enderezo = $email = "";
    $errores = array();


    ?>

    <?php
    if (isset($_POST['update'])) {
        //Login 
        if (isset($_POST['editLogin'])) {
            $login = $_POST['editLogin'];
        } else {
            $editLoginError = "Login Invalido";
            array_push($errores, $editLoginError);
        }

        //Contrasinal
        if (isset($_POST['verificaContrasinal'])) {
            $validaContrasinal = $_POST['verificaContrasinal'];
        } else {
            $editValidarError = "Debe verificar o contrasinal";
            array_push($errores, $editValidarError);
        }

        if (isset($_POST['editContrasinal'])) {
            if (strcmp($_POST['editContrasinal'], $validaContrasinal) == 0) {
                if (DAO::validarPass($_POST['editContrasinal'])) {
                    $contrasinal = DAO::codificar($_POST['editContrasinal']);
                } else {
                    $editContrasinalError = "O contrasinal non cumpre os requisitos";
                    array_push($errores, $editContrasinalError);
                }
            } else {
                $editContrasinalError = "O contrasinal e a verificacion non son iguais";
                array_push($errores, $editContrasinalError);
            }
        }

        //Nome Completo.
        if (isset($_POST['editNome'])) {
            if (DAO::validarTexto($_POST['editNome'])) {
                $nome = $_POST['editNome'];
            } else {
                $editNomeError = "O nome non cumple os requisitos";
                array_push($errores, $editNomeError);
            }
        }

        //Email

        if (isset($_POST['editEmail'])) {
            if (DAO::validarEmail($_POST['editEmail'])) {
                $email = $_POST['editEmail'];
            } else {
                $editEmailError = "O email é invalido";
                array_push($errores, $editEmailError);
            }
        }

        //Enderezo

        if (isset($_POST['editEnderezo'])) {
            $enderezo = $_POST['editEnderezo'];
        } else {
            $editEnderezoError = $_POST['Introduzca Enderezo'];
            array_push($errores, $editEnderezoError);
        }

        if (isset($_POST['editRol'])) {
            $rol = $_POST['editRol'];
        }

        $codigo = $_POST['codigo'];

        if (empty($errores)) {
            $editUser = new Users($rol, $login, $contrasinal, $nome, $enderezo, $email);
            DAO::updateUserBDD($editUser, $codigo);
        }
    }
    if (isset($_POST['volver'])) {
        header("Location: usuarios.php");
    }
    ?>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="form">
            <div class="note">
                <p>Edición de Usuarios</p>
            </div>
        </div>
        <div class="form-content">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="text" name="editRol" class="form-control" value="<?php echo $usuario->getRol() ?>">
                        <?php DAO::erro($editLoginError); ?>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="text" name="editLogin" class="form-control" value="<?php echo $usuario->getLogin() ?>">
                        <?php DAO::erro($editLoginError); ?>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="password" class="form-control" name="editContrasinal" placeholder="Contrasinal">
                        <?php DAO::erro($editContrasinalError); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <input type="text" name="editNome" class="form-control" value="<?php echo $usuario->getNome() ?>">
                        <?php DAO::erro($editNomeError); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="password" class="form-control" name="verificaContrasinal" placeholder="Verifica Contrasinal">
                        <?php DAO::erro($editValidarError); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="text" name="editEnderezo" class="form-control" value="<?php echo $usuario->getEnderezo() ?>">
                        <?php DAO::erro($editEnderezoError); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="email" name="editEmail" class="form-control" value="<?php echo $usuario->getEmail() ?>">
                        <?php DAO::erro($editEmailError); ?>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <button type="submit" name="update" class="btnSubmit">Actualizar</button>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <button type="submit" name="volver" class="btnSubmit">Volver</button>
                    </div>
                </div>
                <input type="hidden" name="codigo" value="<?php echo $usuario->getCodigo() ?>">
            </div>
    </form>

</body>

</html>