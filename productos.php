<!DOCTYPE html>
<?php
//Autor: Oscar González Martinez
//Fecha: 11/11/21
//Version: 1.0
//Proyecto Tienda: Productos.

include "./class/DAO.php";
include "./functions/form.php";
include_once "./class/Users.class.php";
include_once './class/Produtos.class.php';
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Control de Productos</title>
        <?php
        //Configuración del estilo de la página
        if (isset($_COOKIE['estilo'])) {
            echo '<link rel="styleSheet" href="' . $_COOKIE['estilo'] . '"/>';
        } else {
            echo '<link rel="stylesheet" href="./estilos/style.css"/>';
        }
        ?>
    </head>
    <body>
        <?php
        session_start();

//Array que contiene los CSV
        $database = DAO::leerUsuarios("usuarios.csv");
        $stock = DAO::leerProdutos("productos.csv");
        //Inicialización de variables.

        $codigoErro = $nomeErro = $descricionErro = $prezoErro = $unidadesErro = $fotosErro = $iveErro = "";
//Control de Sesiones.

        if (!isset($_SESSION['userSesion'])) {
            die(header("location: login.php"));
        } else if (!DAO::esAdmin($_SESSION['userSesion'], $database)) {
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
            //Inicialización de variables dentro del formulario.
            $codigo = $nome = $descricion = $unidades = $fotos = $prezo = $ive = "";
            //Array de Errores.
            $formError = array();
            //Comprobaciones código.
            if (isset($_POST['codigo'])) {
                //evalúa si el codigo ya existe.
                if (!DAO::findCodeProd($stock, $_POST['codigo'])) {
                    $codigo = $_POST['codigo'];
                } else {
                    //si es incorrecto se genera un error y se añade al array de errores.
                    $codigoErro = "O codigo xa se atopa na base de datos";
                    array_push($formError, $codigoErro);
                }
            }
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
                
                if (isset(($_POST['ive']))){
                    if ($_POST['ive'] == "21" || $_POST['ive'] == '10' || $_POST['ive'] == '4'){
                        $ive = $_POST['ive'];
                    } else {
                        $iveErro = "O valor do ive e: 21,10 ou 4";
                        array_push($formError,$iveErro);
                    }
                }
            } else {
                $fotosErro = "Debe seleccionar unha imaxe";
                array_push($formError, $fotosErro);
            }

            if (empty($formError)) {
                $newProduct = new Produtos($codigo, $nome, $descricion, $unidades, $prezo, $fotos,$ive);
                $dataProducto[] = $newProduct;
                DAO::escribirProductos($dataProducto);
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
        <form method="post" action="<?php $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">
            <table class="prod">
                <tr>                    
                    <td colspan="2">
                        <label for="nome">Nome</label><br/>
                        <input type="text" name="nome" value="<?php
                        if (isset($_POST['nome'])) {
                            echo $_POST['nome'];
                        }
                        ?>"></input>
                               <?php DAO::erro($nomeErro) ?>
                    </td>
                    <td colspan="2">
                        <label for="descricion">Descricion</label><br/>
                        <input type="text" name="descricion" value="<?php
                        if (isset($_POST['descricion'])) {
                            echo $_POST['descricion'];
                        }
                        ?>"/>
                               <?php DAO::erro($descricionErro) ?>
                    </td>
                </tr>                
                <tr>
                    <td>
                        <label for "codigo">Código</label><br/>
                        <input type="number" name="codigo"value="<?php
                        if (isset($_POST['codigo'])) {
                            echo $_POST['codigo'];
                        }
                        ?>"></input>
                               <?php DAO::erro($codigoErro) ?>
                    </td>
                    <td>
                        <label for="unidades">Unidades</label><br/>
                        <input type="number" value="<?php
                        if (isset($_POST['unidades'])) {
                            echo $_POST['unidades'];
                        }
                        ?>" name="unidades"/>
                               <?php DAO::erro($unidadesErro) ?>
                    </td>
                    <td>
                        <label for="prezo">Prezo</label>
                        <input type="number" name="prezo" value="<?php
                        if (isset($_POST['prezo'])) {
                            echo $_POST['prezo'];
                        }
                        ?>"/>
                               <?php DAO::erro($prezoErro) ?>
                    </td>
                    <td>
                        <label for="ive">IVE</label>
                        <input type="number" name="ive" value="<?php
                        if (isset($_POST['ive'])) {
                            echo $_POST['ive'];
                        }
                        ?>"/>
                               <?php DAO::erro($iveErro) ?>
                    </td>                    
                </tr>
                <tr>
                    <td colspan="6">
                        <label for="fotos">Imagen</label>                        
                        <input type="file" name="fotos" value="<?php
                        if (isset($_POST['fotos'])) {
                            echo $_POST['fotos'];
                        }
                        ?>"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="6">
                        <input type="submit" name="engadir"value="Engadir"/>
                        <input type="reset" name="borrar" value="Borrar" />
                    </td>
                </tr>
            </table>
        </form>

        <!-- 
        
        ------------------------------------------------------------------------
                            VISUALIZACION DE PRODUCTOS
        ------------------------------------------------------------------------
        
        -->

        <table class="lista">

            <tr>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Descripcion</th>
                <th>Unidades</th>
                <th>Prezo</th>
                <th>Codigo</th>
            </tr>
            <?php
            foreach ($stock as $productos) {
                ?>
                <tr>
                <form method ="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                    <td><img class="imgStore" src="<?php echo $productos->getFotos() ?>"/></td>
                    <td><input type="text" value="<?php echo $productos->getNome(); ?>"></td>
                    <td><input type="text" value="<?php echo $productos->getDescricion(); ?>" disabled/></td>
                    <td><input type="text" value="<?php echo $productos->getUnidades(); ?>" disabled /></td>
                    <td><input type="text" value="<?php echo $productos->getPrezo(); ?>" disabled/></td>                    
                    <td><input type="submit" value="<?php echo $productos->getCodigo(); ?>" name='edit'/></td>
                    </tr>
                </form>     
                <?php
            }
            ?>
            <!-- 
    
    ------------------------------------------------------------------------
                        EDICION DE PRODUCTOS
    ------------------------------------------------------------------------
    
            -->
        </table>
        <!-- VISUALIZACION DE PRODUCTO SELECCIONADO -->                
        
            <?php
            if (isset($_POST['edit'])) {
                ?>
                <table class="lista">  
             <tr>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Descripcion</th>
                <th>Unidades</th>
                <th>Prezo</th>
                <th>Prezo con IVE</th>
                <th>Codigo</th>
            </tr>
            <?php
                foreach ($stock as $editProductos) {
                    if ($_POST['edit'] == $editProductos->getCodigo()) {
                        $newEditPro = new Produtos($editProductos->getCodigo(), $editProductos->getNome(), $editProductos->getDescricion(), $editProductos->getUnidades(), $editProductos->getPrezo(), $editProductos->getFotos(), $editProductos->getIve());                        
                        ?>
                        <h3>Edita o produto seleccionado</h3>
                        <tr>
                        <form method ="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">
                            <td><input name='editimg' value="<?php echo $newEditPro->getFotos() ?>"/></td>
                            <td><input name="editName" type="text" value="<?php echo $newEditPro->getNome(); ?>"></td>
                            <td><input name="editDesc" type="text" value="<?php echo $newEditPro->getDescricion(); ?>" /></td>
                            <td><input name="editUnit" type="text" value="<?php echo $newEditPro->getUnidades(); ?>"  /></td>
                            <td><input name="editPrize" type="text" value="<?php echo $newEditPro->getPrezo(); ?>" /></td>                    
                            <td><input name="editPrize" type="text" value="<?php echo $newEditPro->prezoConIVE(); ?>" /></td>                    
                            <td><input name="editCode" type="text" value="<?php echo $newEditPro->getCodigo(); ?>" /></td>                    
                            </tr>
                            <tr>
                                <td colspan="7"><input type="submit" value="Fin Visualización" name="editConfirm"/></td>                                
                            </tr>
                        </form>     
                        <?php
                    }
                }
            }
            if (isset($_Post['editConfirm'])){
                header("Refresh:0");
            }
            ?>
        </table>        
    </body>
</html>
