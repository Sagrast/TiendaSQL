<!DOCTYPE html>
<?php
//Autor: Oscar González Martínez
//Fecha: 30/10/2021
//Version: 1.0
//Proyecto Tienda: Perfil
include "./class/DAO.php";


//Recuperamos datos de la sesion.
session_start();

//Si no hay una sesion activa, terminamos el proceso y sacamos un link a la página de login.
if (!isset($_SESSION['userSesion'])) {
    die(header("location: login.php"));
} else {
    //Incluimos menu.php solo si la sesión está iniciada.
    include 'menu.php';
}
//Variables para el formulario.
$cookieStyle = "";
$cookieFont = 12;

//Proceso del formulario.
if (isset($_POST['submit'])) {
    //Estilo
    if (!empty($_POST['UserStyle'])) {
        //Si el $_POST no está vacío establecemos el valor de la cookie a través de la función.
        $cookieStyle = DAO::styleCookie($_POST['UserStyle']);
    } else {
        //Si está vacío no forzamos error, dejamos un tema por defecto.
        $cookieStyle = DAO::styleCookie("white");
    }
    //Tamaño fuente
    if (!empty($_POST['textSize'])) {
        $cookieFont = $_POST['textSize'];
    } else {
        $cookieFont = 12;
    }

    setcookie("estilo", $cookieStyle, time() + 3600);
    setcookie("fuente", $cookieFont, time() + 3600);

    header("Refresh:0");
}

?>
<html>
<?php
include_once "./recursos/head.php";
?>


<body style="font-size: <?php if (isset($_COOKIE['fuente'])) {
                            echo $_COOKIE['fuente'];
                        } ?>px ">
    <section class="vh-100 gradient-custom">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-dark text-white" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">

                            <div class="mb-md-5 mt-md-4 pb-5">
                                <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" class="profile">
                                    <h2 class="fw-bold mb-2 text-uppercase">Preferencias</h2>
                                    <label class="fw-bold mb-2 text-uppercase" for="UserStyle">Estilo</label>
                                    <select id='UserStyle' name='UserStyle'>
                                        <option value="">(Selecciona un tema)</option>
                                        <option value="white">Tema Claro</option>
                                        <option value="dark">Tema Oscuro</option>
                                    </select>
                                    <br />
                                    <label class="fw-bold mb-2 text-uppercase" for="textSize">Tamaño de letra</label>
                                    <input type="number" name="textSize" id="textSize" value="12" min="10" max="30" />
                                    <br />
                                    <input type="submit" name="submit" value="Modificar" />

                                    <input type="reset" name="reset" value="Valores por defecto" />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>