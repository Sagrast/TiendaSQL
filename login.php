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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    
</head>
<body>
    <?php 
        
        //Con los nuevos metodos de validación de usuario no necesito cargar en memoria toda la base de datos.
        //$csv = DAO::userBDD();
        $error = "";
        if (isset($_POST['login'])){
            $usuario = $_POST['user'];
            $pass = $_POST['pass'];
            
            
            if (empty($usuario) || empty($pass)) {
                //Si hay campos vacíos generamos un error.
                $error = "Debe inserir usuario e contrasinal";
            } else {
                //si el usuario existe en la BDD
                if (DAO::validateUserBDD($usuario)){
                    //compara el Hash de la contraseña insertada y la almacenada.
                    if(DAO::comparaHashBDD($usuario,$pass)){                        
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
   
    < class="box"> 
    <div class="container">
        <h1 class="neon">Tenda Online</h1>
        </div>       
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