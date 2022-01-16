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
    if (!isset($_SESSION['userSesion'])){        
        die(header("location: login.php"));
    } else {
        //Incluimos menu.php solo si la sesión está iniciada.
        include 'menu.php';
    }
    //Variables para el formulario.
    $cookieStyle = "";
    $cookieFont = 12;
    
    //Proceso del formulario.
    if (isset($_POST['submit'])){
        //Estilo
        if (!empty($_POST['UserStyle'])){
            //Si el $_POST no está vacío establecemos el valor de la cookie a través de la función.
            $cookieStyle = DAO::styleCookie($_POST['UserStyle']);
        } else {
            //Si está vacío no forzamos error, dejamos un tema por defecto.
            $cookieStyle = DAO::styleCookie("white");
        }
        //Tamaño fuente
        if (!empty($_POST['textSize'])){            
            $cookieFont = $_POST['textSize'];
        } else {
            $cookieFont = 12;
        }
        
        setcookie("estilo",$cookieStyle,time()+3600);
        setcookie("fuente",$cookieFont, time()+3600);
        
        header("Refresh:0");
    }
    
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Perfil</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <?php 
        //Si hay una cookie tipo estilo establecida
          if (isset($_COOKIE['estilo'])) {
              //toma su valor y define el archivo de stilo cargado en la página.
            echo '<link rel="styleSheet" href="'.$_COOKIE['estilo'].'"/>';            
        } else {
            //Si no está establecida dejamos por defecto el tema claro.
            echo '<link rel="stylesheet" href="./estilos/style.css"/>';
        }        
        ?>
        
    </head>
    <body style="font-size: <?php if(isset($_COOKIE['fuente'])) {echo $_COOKIE['fuente'];} ?>px ">
        <h2>Preferencias</h2>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']?>" class="profile">         
            <label for="UserStyle">Estilo</label>
            <select id='UserStyle' name='UserStyle'>
                <option value="">(Selecciona un tema)</option>
                <option value="white">Tema Claro</option>
                <option value="dark">Tema Oscuro</option>
            </select>            
            <br/>
            <label for="textSize">Tamaño de letra</label>
            <input type="number" name="textSize" id="textSize" value="12" min="10" max="30"/>
            <br/>
            <input type="submit" name="submit" value="Modificar" />
            
            <input type="reset" name="reset" value="Valores por defecto"/>
            </form>
        
    </body>
</html>

    