<?php 
    //Autor: Oscar González Martínez
    //Versión: 1.0
    //Fecha: 25/10/2021    
    //Proyecto Tienda: Login
    include "./class/DAO.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <?php 
          if (isset($_COOKIE['estilo'])) {
            echo '<link rel="styleSheet" href="'.$_COOKIE['estilo'].'"/>';            
        } else {
            echo '<link rel="stylesheet" href="./estilos/style.css"/>';
        }        
     ?>
</head>
<body>
    <?php 
        
        $csv = DAO::leerUsuarios("usuarios.csv");
        $error = "";
        if (isset($_POST['login'])){
            $usuario = $_POST['user'];
            $pass = $_POST['pass'];
            
            
            if (empty($usuario) || empty($pass)) {
                //Si hay campos vacíos generamos un error.
                $error = "Debe inserir usuario e contrasinal";
            } else {
                //si el usuario existe en el CSV
                if (DAO::validarUsuario($usuario,$csv)){
                    //compara el Hash de la contraseña insertada y la almacenada.
                    if(DAO::comparaHash($usuario,$pass,$csv)){                        
                        //si todos los campos son correctos, nos dirige a la pagina de usuarios.
                        session_start();
                        $_SESSION['userSesion'] = $usuario;
                        //Al logear generamos una cookie con el nombre del usuario.
                        setcookie("usuario",$usuario,time()+3600);
                        header("Location: perfil.php");
                    } else {
                        $error = "Contrasinal incorrecto";
                    }
                } else {
                    $error = "O Usuario non existe.";
                }
            }
        }
        
    ?>
   
    <div class="box">        
        <h1>Tenda Online</h1>
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            <label for="user">Usuario</label>
            <br/>
                <input type="text" name="user" class="usuario" value="<?php if (isset($_POST['usuario'])) {echo $_POST['login'];} ?>"/>
            <br/>
                <label for="pass">Contraseña</label>
            <br/>
                <input type="password" name="pass" class="contrasinal"/>
            <br/>
            <div class="btn">
                <input type="submit" name="login" value="Login"/>            
                <?php DAO::erro($error) ?>
            </div>
        </form>
        <br/>
        <a href="./rexistro.php">Novo Usuario</a>
    </div>   
    
</body>
</html>