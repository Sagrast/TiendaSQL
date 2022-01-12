<?php
/*
 * @author: Oscar González Martínez
 * @version: 1.0
 * Fecha: 06/11/2021
 */

    include "./class/DAO.php";
    include "./functions/form.php";
    include_once "./class/Users.class.php";
    $database = DAO::leerUsuarios("usuarios.csv");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Rexistro</title>
        <link rel="stylesheet" href="./estilos/style.css"/>
    </head>
    <body>
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
        if (isset($_POST['gardar'])){
        //Comprobamos que todos los campos tengan valor antes de llamar a la funcion 
        //De insercion en el CSV.
            if (isset($_POST['login'])){
                $login = $_POST['login'];
            } else {
                $loginError = "O campo está vacío";
                array_push($erros,$loginError);
            }           
            
            //Correo electronico.
            if (isset($_POST['email'])){
                $email = $_POST['email'];
            } else {
                $emailError = "Introduza unha dirección de correo";
                array_push($erros, $emailError);               
            }
        
            //Validar 
            //Añadimos en una variable el campo escrito en validar.
            if (isset($_POST['validar'])){
                $validar = $_POST['validar'];
            } else {
                //Si está vacío generamos un error y lo añadimos al array.
                $validarError = "Debe verificar o contrasinal";
                array_push($erros,$validarError);
            }
            
            //Contrasinal 
            if(isset($_POST['pass'])){
                //Si está cubierto el campo password lo comparamos con el almacenado en validar.
                if(strcmp($_POST['pass'], $validar) === 0){
                    //Si los valores son iguales, hacemos la verificación de la contraseña mediante la función creada
                    $contrasinal = $_POST['pass'];                    
                } else {
                  //En caso contrario generamos los errores correspondientes y los almacenamos en el array.
                    $contrasinalError = "As contrasinais non son iguais";
                    array_push($erros, $contrasinalError);  
                }
            } else {
               $contrasinalError = $validarError = "Introduzca Contrasinal";
               array_push($erros,$validarError);
            }
            
            //Nome completo.
            if (isset($_POST['fullName'])){
                $nomeCompleto = $_POST['fullName'];                
            } else {
                $nomeCompletoError = "Introduzca Nome";
                arra_push($erros,$nomeCompletoError);
            }
            
             //Direccion.
            if (isset($_POST['address'])){
                $enderezo = $_POST['address'];                
            } else {
                $enderezoError = "Introduzca direccion";
                array_push($erros,$enderezoError);
            }
            
            //Si todo está correcto, generamos un nuevo objeto Usuario y lo almacenamos en el CSV.
            if(empty($erros)){
                form("Usuario",$login,$contrasinal,$nomeCompleto,$enderezo,$email,$database);                
            }
        }
        
        ?>
        <!-- Creación de formulario de registro -->
        <form method ="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">

            <h1>Rexistro</h1>
            <table class="lista">
                <tr>
                    <td>
                        <label for="nome">Login</label>
                        <input type="text" name="login" value="<?php if (isset($_POST['login'])) {
            echo $_POST['login'];
        } ?>"></input>
                        <?php DAO::erro($loginError) ?>
                    </td>
                    <td>
                        <label for="email">Correo Electronico</label>
                        <input type="text" name="email" value="<?php if (isset($_POST['email'])) {
            echo $_POST['email'];
        } ?>"/>
                        <?php DAO::erro($emailError) ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="password">Contrasinal</label>
                        <input type="password" name="pass"/>
                        <?php DAO::erro($contrasinalError) ?>
                    </td>
                    <td>
                        <label for="verifica">Verificar contrasinal</label>
                        <input type="password" name="validar"/>
                        <?php DAO::erro($validarError) ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="fullName">Nome Completo</label>
                        <input type="text" name="fullName" value="<?php if (isset($_POST['fullName'])) {
            echo $_POST['fullName'];
        } ?>"/>
                        <?php DAO::erro($nomeCompletoError) ?>
                    </td>
                    <td>
                        <label for="address">Enderezo</label>
                        <input type="text" name="address" value="<?php if (isset($_POST['address'])) {
            echo $_POST['address'];
        } ?>"/>
                        <?php DAO::erro($enderezoError) ?>
                    </td>
                </tr>
            </table>
            <br/>
            <input type="submit" name="gardar" value="Gardar"/>
        </form>
    </body>
</html>
