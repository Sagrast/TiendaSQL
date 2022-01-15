<?php
//Autor: Oscar González Martínez
//Version: 1.0
//Fecha: 30-10-2021
//Proyecto Tienda: Usuarios

include "./class/DAO.php";
include "./functions/form.php";
include_once "./class/Users.class.php";

//Recuperación de inicio de sesion.
session_start();

//Array que contiene el CSV
$database = DAO::userBDD();

if (!isset($_SESSION['userSesion'])) {
    die(header("location: login.php"));
} else if (!DAO::esAdminBDD($_SESSION['userSesion'])) {
    //Si la función esAdmin() devuelve falso, el usuario no podrá entrar en la página.
    die("Non es administrador. <a href='login.php'>identificarse</a>.<br/>");
} else {
    //Incluimos menu.php solo si la sesión está iniciada.
    include 'menu.php';
}


$rolError = "";
$loginError = "";
$contrasinalError = "";
$adminError = "No eres Administrador";
$nomeCompError = "";
$direccionError = "";
$correoError = "";
//Array para escribir en el CSV
$errores = array();

if (isset($_POST['engadir'])) {
    if (DAO::esAdminBDD($_SESSION['userSesion'])) {
        //Rol
        if (isset($_POST['rol'])) {
            if (!empty($_POST['rol'])) {
                $rol = $_POST['rol'];
            } else {
                $rolError = "Debe seleccionar un rol";
                array_push($errores, $rolError);
            }
        } else {
            $rolError = "Debe seleccionar un rol";
            array_push($errores, $rolError);
        }
        //Login
        if (isset($_POST['login'])) {
            $login = $_POST['login'];
        } else {
            $loginError = "O campo está vacío";
            array_push($errores, $loginError);
        }
        //Contrasinal
        if (isset($_POST['contrasinal'])) {
            //Si la funcion validar contraseña devuelve Verdadero codificamos la contraseña.
            $contrasinal = $_POST['contrasinal'];
        } else {
            $contrasinalError = "Introduzca Contrasinal";
            array_push($errores, $contrasinalError);
        }
        //Nome Completo.
        if (isset($_POST['nomeCompleto'])) {
            $nomeCompleto = $_POST['nomeCompleto'];
        } else {
            $nomeCompError = "Valores incorrectos";
            array_push($errores, $nomeCompError);
        }

        //Direccion.
        if (isset($_POST['direccion'])) {
            $enderezo = $_POST['direccion'];
        } else {
            $direccionError = "Carácteres inválidos na direccion";
            array_push($errores, $direccionError);
        }


        //email
        if (isset($_POST['correo'])) {
            $email = $_POST['correo'];
        } else {
            $correoError = "Introduzca una dirección de correo";
            array_push($errores, $correoError);
        }


        //Si el array tiene todos los campos cubiertos correctamente, lo escribimos en el CSV.
        if (empty($errores)) {
            form($rol, $login, $contrasinal, $nomeCompleto, $enderezo, $email);
            header("Refresh:0");
        }
    } else {
        DAO::erro($adminError);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">    
        <title>Usuarios</title>
        <?php
        if (isset($_COOKIE['estilo'])) {
            echo '<link rel="styleSheet" href="' . $_COOKIE['estilo'] . '"/>';
        } else {
            echo '<link rel="stylesheet" href="./estilos/style.css"/>';
        }
        ?>
    </head>
    <body style="font-size: <?php
    if (isset($_COOKIE['fuente'])) {
        echo $_COOKIE['fuente'];
    }
    ?>px ">    
        <h1>Novo Usuario</h1>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
            <select id="rol" name="rol"/>            
            <option value="">(Selecciona un Rol)</option>            
            <?php
            //Creamos un array de roles por si hubiese que ampliar roles en un futuro.
            $roles = array("Administrador", "Usuario");
            foreach ($roles as $rol) {
                echo '<option value="' . $rol . '">' . $rol . '</option>';
            }
            ?>
            <br/>
        </select>
        <?php DAO::erro($rolError) ?>

        <br/>
        <label for="usuario">Usuario</label><br/>
        <input type="text" name="login" value="<?php
        if (isset($_POST['usuario'])) {
            echo $_POST['usuario'];
        }
        ?>" class="usuario"/>
               <?php DAO::erro($loginError) ?>
        <br/>
        <label for="contrasinal">Contrasinal</label><br/>
        <input type="password" name="contrasinal" class="contrasinal"/>
        <br/>
        <?php DAO::erro($contrasinalError) ?>
        <br/>
        <label for="nomeCompleto">Nome e Apelidos</label>
        <input type="text" name="nomeCompleto" class="usuario" value="<?php
        if (isset($_POST['nomeCompleto'])) {
            echo $_POST['nomeCompleto'];
        }
        ?>"/>
               <?php DAO::erro($nomeCompError) ?>
        <label for="direccion">Direccion</label>
        <input type="text" class="usuario" name="direccion" value="<?php
        if (isset($_POST['direccion'])) {
            echo $_POST['direccion'];
        }
        ?>"/>            
               <?php DAO::erro($direccionError) ?>
        <label for ="correo">Email</label>
        <input type="text" class="usuario" name="correo" value="<?php
        if (isset($_POST['correo'])) {
            echo $_POST['correo'];
        }
        ?>"/>
               <?php DAO::erro($correoError) ?>
        <br/>
        <input type="submit" value="engadir" name="engadir"/>
    </form>

    <br/>

    <table class="lista">
        <tr>
            <th>Rol</th>
            <th>Login</th>
            <th>Contrasinal</th>
            <th>Nome Completo </th>
            <th>Enderezo</th>
            <th>Email</th>
            <th>Operacions</th>
        </tr>
        <?php
//Creación de la tabla.    
        $contFila = 0;
        foreach ($database as $datos) {
            ?>
            <tr>
                <td><?php echo $datos->getRol() ?></td>
                <td><?php echo $datos->getLogin() ?></td>
                <td><?php echo $datos->getContrasinal() ?></td>
                <td><?php echo $datos->getNome() ?></td>
                <td><?php echo $datos->getEnderezo() ?></td>
                <td><?php echo $datos->getEmail() ?></td>                                
                <td><a href="borrarUsuario.php?id=<?php echo $datos->getCodigo(); ?>">Eliminar</a></td>
            </tr>
            <?php            
        }
        ?>    
    </table>

</body>
</html>