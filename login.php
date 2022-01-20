<?php 
    //Autor: Oscar González Martínez
    //Versión: 1.0
    //Fecha: 25/10/2021    
    //Proyecto Tienda: Login
    //Revisión CSS-Bootstrap: 18/01/2022
    include "./class/DAO.php";    
?>
<!DOCTYPE html>
<html lang="en">
<?php
    include "./recursos/head.php";    
?>


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
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<section class="vh-100 gradient-custom">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card bg-dark text-white" style="border-radius: 1rem;">
          <div class="card-body p-5 text-center">

            <div class="mb-md-5 mt-md-4 pb-5">

              <h2 class="fw-bold mb-2 text-uppercase">Login</h2>
              <p class="text-white-50 mb-5">Introduce tu Usuario y Contraseña!</p>

              <div class="form-outline form-white mb-4">
                <input type="text" id="user" name="user" class="form-control form-control-lg" />
                <label class="form-label" for="user">Login</label>
              </div>

              <div class="form-outline form-white mb-4">
                <input type="password" id="pass" name="pass" class="form-control form-control-lg" />
                <label class="form-label" for="pass">Password</label>
              </div>              

              <button class="btn btn-outline-light btn-lg px-5" name="login" type="submit">Login</button>

              <div class="d-flex justify-content-center text-center mt-4 pt-1">
                <a href="#!" class="text-white"><i class="fab fa-facebook-f fa-lg"></i></a>
                <a href="#!" class="text-white"><i class="fab fa-twitter fa-lg mx-4 px-2"></i></a>
                <a href="#!" class="text-white"><i class="fab fa-google fa-lg"></i></a>
              </div>

            </div>

            <div>
              <p class="mb-0">¿No tienes cuenta? <a href="./rexistro.php" class="text-white-50 fw-bold">Registrate!</a></p>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
    </form>
</body>

</html>