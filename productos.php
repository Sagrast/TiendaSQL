<!DOCTYPE html>
<?php
//Autor: Oscar González Martinez
//Fecha: 11/11/21
//Version: 1.0
//Proyecto Tienda: Productos.

include "./class/DAO.php";
include "./functions/form.php";
include_once "./class/Users.class.php";
include_once "./class/Produtos.class.php";
?>
<html>

<?php
include "./recursos/head.php";
?>

<body>
    <?php
    session_start();

    //Array que contiene los CSV
    //$database = DAO::userBDD();
    $stock = DAO::productsBDD();
    //Inicialización de variables dentro del formulario.
    $codigo = $nome = $descricion = $unidades = $fotos = $prezo = $ive = "";
    
    $codigoErro = $nomeErro = $descricionErro = $prezoErro = $unidadesErro = $fotosErro = $iveErro = "";
    //Control de Sesiones.

    if (!isset($_SESSION['userSesion'])) {
        die(header("location: login.php"));
    } else if (!DAO::esAdminBDD($_SESSION['userSesion'])) {
        //Si la función esAdmin() devuelve falso, el usuario no podrá entrar en la página.
        die("Non es administrador. <a href='login.php'>identificarse</a>.<br/>");
    } else {
        //Incluimos menu.php solo si la sesión está iniciada.
        include 'menu.php';
    }
    ?>
    <!-- FORMULARIO ENGADIR PRODUCTOS -->
    <?php    
    if (isset($_POST['engadir']) && isset($_FILES['fotos'])) {
        
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
        
        var_dump($fotos,$descricion);
        if (empty($formError)) {
            $newProduct = new Produtos($nome, $descricion, $unidades, $prezo, $fotos, $ive);                        
            DAO::escribirProductosBDD($newProduct);
            header("Refresh:0");
        }
    }
    
    ?>

    
    <!-- 
        
        ------------------------------------------------------------------------
                        Formulario para engadir productos 
        ------------------------------------------------------------------------
        
        -->
    <h1> Engadir Productos </h1>
 <section class="gradient-custom">
        <div class="container register-form py-5 h-100"">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>"  enctype="multipart/form-data">
                <div class="form">
                    <div class="note">
                        <p>Benvidos a RetroTenda.</p>
                    </div>
                    <div class="form-content">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                <label class="form-label" for="nome">Nome</label><br />
                    <input type="text" name="nome" class="form-control" value="<?php
                                                                                if (isset($_POST['nome'])) {
                                                                                    echo $_POST['nome'];
                                                                                }
                                                                                ?>"></input>
                    <?php DAO::erro($nomeErro) ?>
                                </div>
                                <div class="form-group">
                                <label class="form-label" for="descricion">Descricion</label><br />
                    <input type="text" name="descricion" class="form-control" value="<?php
                                                                                        if (isset($_POST['descricion'])) {
                                                                                            echo $_POST['descricion'];
                                                                                        }
                                                                                        ?>" />
                    <?php DAO::erro($descricionErro) ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label class="form-label" for="unidades">Unidades</label><br />
                    <input type="number" class="form-control" value="<?php
                                                                    if (isset($_POST['unidades'])) {
                                                                        echo $_POST['unidades'];
                                                                    }
                                                                    ?>" name="unidades" />
                    <?php DAO::erro($unidadesErro) ?>
                                </div>
                                <div class="form-group">
                                <label class="form-label" for="prezo">Prezo</label>
                <input type="number" class="form-control" name="prezo" value="<?php
                                                                                if (isset($_POST['prezo'])) {
                                                                                    echo $_POST['prezo'];
                                                                                }
                                                                                ?>" />
                <?php DAO::erro($prezoErro) ?>
                                </div>
                            </div>                            
                            <div class="col-md-6">
                                <div class="form-group">
                                <label class="form-label" for="ive">IVE</label>
                <input type="number" class="form-control" name="ive" value="<?php
                                                                            if (isset($_POST['ive'])) {
                                                                                echo $_POST['ive'];
                                                                            }
                                                                            ?>" />
                <?php DAO::erro($iveErro) ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label class="form-label" for="fotos">Imagen</label>
        <input type="file" name="fotos" value="<?php
                                                if (isset($_POST['fotos'])) {
                                                    echo $_POST['fotos'];
                                                }
                                                ?>" />

                                </div>
                            </div>
                        </div>                        
                        <button type="submit" value="engadir" name="engadir" class="btnSubmit">Enviar</button>                        
                    </div>
                </div>
        </div>
        </form>
    
    

    <!-- 
        
        ------------------------------------------------------------------------
                            VISUALIZACION DE PRODUCTOS
        ------------------------------------------------------------------------
        
        -->

    <table class="table table-striped table-dark">

        <tr>
            <th>Imagen</th>
            <th>Nombre</th>
            <th>Descripcion</th>
            <th>Unidades</th>
            <th>Prezo</th>            
            <th>Operacions</th>
        </tr>
        <?php
        foreach ($stock as $productos) {
        ?>
            <tr>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                    <td><img class="imgStore" src="<?php echo $productos->getFotos() ?>" /></td>
                    <td><?php echo $productos->getNome(); ?></td>
                    <td><?php echo $productos->getDescricion(); ?></td>
                    <td><?php echo $productos->getUnidades(); ?>"</td>
                    <td><?php echo $productos->getPrezo(); ?></td>                    
                    <td><a href="borrarProd.php?id=<?php echo $productos->getCodigo(); ?>">Eliminar </a> /
                        <a href="updateProd.php?id=<?php echo $productos->getCodigo(); ?>"> Modificar</a></td>
            </tr>
            </form>
        <?php
        }
        ?>
        
    
    
        </section>
</body>

</html>