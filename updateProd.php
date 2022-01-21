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
    $id = "";
    $adminError = "No eres Administrador";
    if (!empty($_GET['id']) && isset($_GET['id'])){
        $id = $_GET['id'];
    } else if (isset($_POST['codigo'])){
        $id = $_POST['codigo'];
    }    
    $producto = DAO::findProductsBDD($id);
    $codigoErro = $nomeErro = $descricionErro = $prezoErro = $unidadesErro = $fotosErro = $iveErro = "";    
    $errores = array();     
    var_dump($_POST);
    ?>   
      <?php
    if (isset($_POST['engadir']) && isset($_FILES['fotos'])) {
        
        //Inicialización de variables dentro del formulario.
        $codigo = $nome = $descricion = $unidades = $fotos = $prezo = $ive = "";
        $codigo = $_POST['codigo'];
        //Array de Errores.
        $formError = array();
        //Comprobaciones Nome
        //Se evalua si el texto es correcto, si no lo es se genera un error.
        if (isset($_POST['nome'])) {
            if (DAO::validarTexto($_POST['nome'])) {
                $nome = $_POST['nome'];
            } else {
                $nomeErro = "Nome invalido";
                array_push($formError, $nomeErro);
            }
        }
        //Comprbaciones descricion.
        //Se evalua si el texto es correcto de lo contrario se genera un error.
        if (isset($_POST['descricion'])) {
            if (DAO::validarTexto($_POST['descricion'])) {
                $descricion = $_POST['descricion'];
            } else {
                $descricionErro = "Descricion invalida";
                array_push($formError, $descricionErro);
            }
        }
        //comprobacion unidades.
        //Se evalúa si el valor es un número entero, de lo contrario se genera un error.
        if (isset($_POST['unidades'])) {
            //No necesitamos una comprobación extra del valor, ya que el typo number del input solo valida enteros.
            $unidades = $_POST['unidades'];
        } else {
            $unidadesErro = "Introduzca un valor.";
            array_push($formError, $unidadesErro);
        }

        //Comprobacion prezo
        if (isset($_POST['prezo'])) {
            if (is_numeric($_POST['prezo'])) {
                $prezo = $_POST['prezo'];
            } else {
                $prezoErro = "O valor non é un enteiro";
                array_push($formError, $prezoErro);
            }
        }
        /*
              ------------------------ SUBIR IMAGENES -----------------------------------------
             */

        if (!empty($_FILES['fotos'])) {
            $dir = "./img/";
            $extensionesValidas = array("jpg", "png", "gif");
            $errores = array();
            $nombreArchivo = $_FILES['fotos']['name'];
            $tamArchivo = $_FILES['fotos']['size'];
            $tempDir = $_FILES['fotos']['tmp_name'];
            $tipoArchivo = $_FILES['fotos']['type'];
            $arrayArchivo = pathinfo($nombreArchivo);
            $extension = $arrayArchivo['extension'];
            //Comprobación de extensiones
            if (!in_array($extension, $extensionesValidas)) {
                $errores[] = "A extensión do arquivo non é valida";
            }
            //Dar nombre al archivo.
            $nombreArchivo = $arrayArchivo['filename'];
            $nombreArchivo = preg_replace("/[^A-Z0-9._-]/i", "_", $nombreArchivo);
            $nombreArchivo = $nombreArchivo . rand(1, 100);
            //si no hay errores se  mueve a su carpeta de forma definitiva.
            if (empty($erros)) {
                $nombreCompleto = $dir . $nombreArchivo . "." . $extension;
                move_uploaded_file($tempDir, $nombreCompleto);
                print "O arquivo subiuse correctamente o servidor.";
            }


            //Se establece el valor de la variable fotos con el resultado de las comprobaciones previas.
            $fotos = $nombreCompleto;

            //IVE

            if (isset(($_POST['ive']))) {
                if ($_POST['ive'] == "21" || $_POST['ive'] == '10' || $_POST['ive'] == '4') {
                    $ive = $_POST['ive'];
                } else {
                    $iveErro = "O valor do ive e: 21,10 ou 4";
                    array_push($formError, $iveErro);
                }
            }
        } else {
            $fotosErro = "Debe seleccionar unha imaxe";
            array_push($formError, $fotosErro);
        }
        
        var_dump($codigo);
        if (empty($formError)) {            
            $newProduct = new Produtos($nome,$descricion,$unidades,$prezo,$fotos,$ive);
            var_dump($newProduct);
            DAO::updateProdBDD($newProduct,$codigo);                
            
        }
        
    }
    if (isset($_POST['volver'])) {
        header("Location: productos.php");
    }
    ?> 

<body>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>"  enctype="multipart/form-data">
        <div class="form">
            <div class="note">
                <p>Edición de Usuarios</p>
            </div>
        </div>
        <div class="form-content">
            <div class="row">
            <div class="col-md-6">
                    <div class="form-group">
                        <label for="editFoto">Imaxe</label>
                        <input type="file" name="fotos" class="form-control" value="<?php echo $producto->getFotos() ?>">
                        <?php DAO::erro($fotosErro); ?>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="editNome">Nome</label>
                        <input type="text" name="nome" class="form-control" value="<?php echo $producto->getNome() ?>">
                        <?php DAO::erro($nomeErro); ?>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="editDesc">Descricion</label>
                        <input type="text" class="form-control" name="descricion" value="<?php echo $producto->getDescricion() ?>">
                        <?php DAO::erro($descricionErro); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="editUnits">Unidades</label>
                        <input type="number" name="unidades" class="form-control" value="<?php echo $producto->getUnidades() ?>">
                        <?php DAO::erro($unidadesErro); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="editPrezo">Prezo</label>
                        <input type="number" class="form-control" name="prezo" value="<?php echo $producto->getPrezo() ?>">
                        <?php DAO::erro($prezoErro); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="editIve">Ive</label>
                        <input type="number" name="ive" class="form-control" value="<?php echo $producto->getIve() ?>">
                        <?php DAO::erro($iveErro); ?>
                    </div>
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
                <input type="hidden" name="codigo" value="<?php echo $producto->getCodigo() ?>">                
            </div>
    </form>

  

</body>

</html>