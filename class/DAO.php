<?php
include_once "./class/Users.class.php";
include_once "./class/Produtos.class.php";
include_once "./class/Connect.class.php";
class DAO
{


    //Autor: Oscar González Martinez
    //Version: 1.0
    //Fecha: 30/10/2021
    //Proyecto Tienda: DAO

    /*

----------------------------------------------------------------------------------------------
                            METODOS PARA BDDD
----------------------------------------------------------------------------------------------

*/
    /* 
------------------------------------ LEER Productos BDD -------------------------------------------
*/
    //Metodo para realizar una consulta a la BDD mediante PDO


    public static function productsBDD()
    {
        //Creamos un  objeto de la clase connect.  
        $conexion = new Connect();
        //Creamos una variable que llama al metodo conexion() de la clase Connect.
        $con = $conexion->conexion();
        //Creamos una consulta preparada.    
        $select = $con->prepare('SELECT * FROM productos');
        //Y la ejecutamos.
        $select->execute();
        //Creamos un array que contendrá el resultado de la consulta SQL
        $data = array();
        //Y creamos un array el que guardaremos los objetos instanciados.
        $resultado = array();
        //Si la consulta no es correcta
        if (!$select) {
            //Generamos un error.
            $DATA['Error'] = "Error de Consulta de Conexión";
        } else {
            //Si es correcta recorremos cada fila de la consulta usando la propiedad de PDO de array asociativo, creando así un array
            //Cuyo indice será el nombre de la columna y valor el contenido de la fila correspondiente a la columna.
            while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
        }
        //Usando el array asociativo anterior instanciamos objetos de la clase Productos para poder así user los metodos propios de esta clase.
        foreach ($data as $d) {
            $producto = new Produtos($d['nome'], $d['descricion'], $d['unidades'], $d['prezo'], $d['fotos'], $d['ive']);
            $producto->setCodigo($d['codigoProd']);
            $resultado[] = $producto;
        }
        return $resultado;
    }

    /*
  --------------------- LEER BDD USUARIOS --------------------------------------------
 */
    /* 
    En  este metodo repetimos los mismos pasos que con la clase producto adaptandolos a la clase Usuarios.
*/

    public static function userBDD()
    {
        $conexion = new Connect();
        $con = $conexion->conexion();
        $select = $con->prepare('SELECT * FROM usuarios');
        $select->execute();
        $data = array();
        $resultado = array();
        if (!$select) {
            $DATA['Error'] = "Error de Consulta de Conexión";
        } else {
            while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
        }
        foreach ($data as $d) {
            $usuario = new Users($d['rol'], $d['userLogin'], $d['contrasinal'], $d['nome'], $d['enderezo'], $d['email']);
            $usuario->setCodigo($d['codigoUser']);
            $resultado[] = $usuario;
        }

        return $resultado;
    }
    /*
  -------------------------------------- VALIDAR USUARIO ----------------------------------------
 */

    public static function validateUserBDD($login)
    {
        //Abrimos conexion
        $conexion = new Connect();
        $con = $conexion->conexion();
        //Se prepara la consulta
        $query = $con->prepare('SELECT userLogin FROM usuarios WHERE userLogin = :userLogin');
        //Enlazmos parametros
        $query->bindParam(":userLogin", $login);
        //Se ejecuta la consulta e inicializamos dos variables. El array que contendrá la respuesta de la consulta y un booleano que será el retorno de la función.
        $query->execute();
        //Si el numero de filas devuelto es igual a 0, el usuario no existe. 
        if ($query->rowCount() == 0) {
            $existe = false;
        } else {
            $existe = true;
        }

        return $existe;
    }


    /*
  -------------------------------------- BORRAR USUARIO ----------------------------------------
 */

    //Recibe una ID y ejecuta una consulta SQL.
    public static function deleteBDD($id)
    {
        //Al igual que en metodos anteriores, repetimos los pasospara preparar la conexión a la BDD 
        //a través de su clase.
        $ok = true;
        $conexion = new Connect();
        $con = $conexion->conexion();
        //Se abre una transaccion.
        $con->beginTransaction();
        //Preparamos la consulta que vamos a lanzar
        $delete = $con->prepare('DELETE FROM usuarios WHERE codigoUser = (:codigo)');
        //Asociamos el parametro :codigo, con la $id que recibe la función.
        $delete->bindParam(":codigo", $id);
        //Se ejecuta la consulta y si el resultado es igual a 0 cambiamos la variable OK a falsa.
        if ($delete->execute() == 0) {
            $ok = false;
        }

        //Si todo ha ido bien $ok sigue en True, se confirma la transacción. De lo contrario revertimos los cambios.
        if ($ok) {
            $con->commit();
        } else {
            $con->rollBack();
        }
        //$delete->execute();    
    }
    /*
  -------------------------------------- Escribir USUARIOS BDD ----------------------------------------
 */

    public static function escribirUsuariosBDD($datos)
    {
        $ok = true;
        //Preparación de conexión, inicio de transacción y consulta.
        $conexion = new Connect();
        $con = $conexion->conexion();
        $con->beginTransaction();
        $insert = $con->prepare('INSERT INTO usuarios VALUES (NULL,:rol,:userLogin,:contrasinal,:nome,:enderezo,:email)');
        //Recoger en variables los paraemetros del objeto recibido.
        $rol = $datos->getRol();
        $login = $datos->getLogin();
        $pass = $datos->getContrasinal();
        $nome = $datos->getNome();
        $enderezo = $datos->getEnderezo();
        $mail = $datos->getEmail();
        //Enlazar los parametros con la consulta preparada.
        $insert->bindParam(':rol', $rol);
        $insert->bindParam(':userLogin', $login);
        $insert->bindParam(':contrasinal', $pass);
        $insert->bindParam(':nome', $nome);
        $insert->bindParam(':enderezo', $enderezo);
        $insert->bindParam(':email', $mail);
        if ($insert->execute() == 0) {
            $ok = false;
        }

        if ($ok) {
            $con->commit();
        } else {
            $con->rollBack();
        }

        return $ok;
    }

    /*
  ----------------------------------------- Escribir Cesta --------------------------------- 
 */

    function escribirProductosBDD($productos)
    {
        $ok = true;
        //Preparación de la conexión, inicio de transacción y consulta.
        $conexion = new Connect();
        $con = $conexion->conexion();
        $con->beginTransaction();
        $insert = $con->prepare("INSERT INTO productos VALUES (NULL,:nome,:descricion,:unidades,:prezo,:foto,:ive)");
        $nome = $productos->getNome();
        $desc = $productos->getDescricion();
        $unidades = $productos->getUnidades();
        $prezo = $productos->getPrezo();
        $foto = $productos->getFotos();
        $ive = $productos->getIve();
        $insert->bindParam(':nome', $nome);
        $insert->bindParam(':descricion', $desc);
        $insert->bindParam(':unidades', $unidades);
        $insert->bindParam(':prezo', $prezo);
        $insert->bindParam(':foto', $foto);
        $insert->bindParam(':ive', $ive);

        if ($insert->execute() == 0) {
            $ok = false;
        }

        if ($ok) {
            $con->commit();
        } else {
            $con->rollBack();
        }

        return $ok;
    }
    /*
  ----------------------------------------- Escribir Cesta ---------------------------------
 */

    public static function escribirCestaBDD($cesta, $usuario, $importe)
    {
        //Inicialicion de variables
        $ok = true;
        //Fecha para el formato DATETIME SQL
        $fecha = date_create()->format('Y-m-d H:i:s');
        //Inicio de  Conexión y transaccion
        $conexion = new Connect();
        $con = $conexion->conexion();
        $con->beginTransaction();
        //Preparación de consultas.
        $pedido = $con->prepare('INSERT INTO pedidos VALUES (NULL,:fecha,:total,:codigoUser)');
        $union = $con->prepare('INSERT INTO rel_pedidos VALUES (:codPedido,:codProd,:cant)');
        //Asociar parametros a variables.
        $codigo = $cesta[0];
        $cantidad = $cesta[1];
        $user = $usuario;
        //Asociar parametros a consultas.
        $pedido->bindParam(":fecha", $fecha);
        $pedido->bindParam(":total", $importe);
        $pedido->bindParam(":codigoUser", $user);

        //Se lanza la primera consulta
        if ($pedido->execute() == 0) {
            $ok = false;
        }

        //Obtenemos el último ID de la anterior consulta y lo asociamos al codigo de pedido de la siguiente consulta.
        $codPedido = $con->lastInsertId();
        $union->bindParam(":codPedido", $codPedido);
        $union->bindParam(":codProd", $codigo);
        $union->bindParam(":cant", $cantidad);


        if ($union->execute() == 0) {
            $ok = false;
        }
        //Si  todo es correcto realizamos el commit.

        if ($ok) {
            $con->commit();
        } else {
            $con->rollBack();
        }
    }

 /*
  ----------------------------------------- Actualizar Usuarios ---------------------------------
 */    
    public static function updateUserBDD($datos,$id){
        $ok = true;
        $conexion = new Connect();
        $con = $conexion->conexion();
        $con->beginTransaction();
        //Preparacion da consulta;
        $update = $con->prepare("UPDATE usuarios SET userLogin = :userLogin, contrasinal = :contrasinal, nome = :nome, enderezo = :enderezo,email = :email WHERE codigoUser = :codigo");
        //Enlazar parametros.        
        $login = $datos->getLogin();
        $pass = $datos->getContrasinal();
        $nome = $datos->getNome();
        $enderezo = $datos->getEnderezo();
        $mail = $datos->getEmail();        
        $update->bindParam(':userLogin', $login);
        $update->bindParam(':contrasinal', $pass);
        $update->bindParam(':nome', $nome);
        $update->bindParam(':enderezo', $enderezo);
        $update->bindParam(':email', $mail);
        $update->bindParam(':codigo',$id);
        var_dump($update);
        if ($update->execute() == 0) {
            $ok = false;
        }

        if ($ok) {
            $con->commit();
        } else {
            $con->rollBack();
        }
    }

    /*
  -------------------------------------- COMPARAR HASH BDD------------------------------------------
 */

    public static function comparaHashBDD($user, $pass)
    {
        //Como siempre, abrimos conexión a MySQL
        $conexion = new Connect();
        $con = $conexion->conexion();
        //Preparamos la consulta
        $query = $con->prepare("SELECT contrasinal FROM usuarios WHERE userLogin = :userLogin");
        //Vinculamos el parametro recibido a la consulta. Y ejecutamos.
        $query->bindParam(":userLogin", $user);
        $query->execute();
        //Almacenamos el array que devuelve.
        $cont = $query->fetch(PDO::FETCH_ASSOC);
        //Y nos quedamos con el valor que necesitamos en una variable, pues Crypt da error si recibe un array en vez de el String.
        $contrasinal = $cont['contrasinal'];
        //Inicializamos un booleano que será la respuesta de la función.    
        $existe = false;
        //Pasamos los datos por la función Hash Equals con un Crypt y nos devolverá verdadero si ambos hash son correctos.
        if (hash_equals($contrasinal, crypt($pass, $contrasinal))) {
            $existe = true;
        }
        return $existe;
    }

    /*
  -------------------------------------- COMPROBAR ROL DE USUARIO CON BDD ------------------------------------------
 */

    public static function esAdminBDD($usuario)
    {
        //Iniciamos una variable a falso.    
        $admin = false;
        //Preparamos la conexión, Query y asociamos parametros.
        $conexion = new Connect();
        $con = $conexion->conexion();
        $query = $con->prepare("SELECT rol FROM usuarios WHERE userLogin = :userLogin");
        $query->bindParam(":userLogin", $usuario);
        $query->execute();
        //almacenamos el resultado en un array.
        $rol = $query->fetch(PDO::FETCH_ASSOC);
        //Hacemos una comparación del valor del array con la cadena 'Administrador', si es correcto devolvemos verdadero.
        if (strcasecmp($rol['rol'], 'Administrador') == 0) {
            $admin = true;
        }

        return $admin;
    }

    /*
  ----------------------------------------- Buscar Producto ---------------------------------

 */

    public static function buscarProductoBDD($codigo)
    {
        //Iniciamos variable que responderá si existe un producto o no.
        $existe = true;
        //Preparamos conexión a la BDD
        $conexion = new Connect();
        $con = $conexion->conexion();
        //preparamos consulta
        $query = $con->prepare("SELECT nome from productos WHERE codigoProd = :codigoProd");
        $query->bindParam(":codigoProd", $codigo);
        //Ejecutamos consulta.
        $query->execute();
        //Contamos las filas devueltas por la consulta. Si es igual a 0, el producto no existe.
        if ($query->rowCount() == 0) {
            $existe = false;
        }

        return $existe;
    }

    public static function buscarUserBDD($codigo)
    {
        $conexion = new Connect();
        $con = $conexion->conexion();
        //Preparamos consulta.
        $query = $con->prepare("SELECT * from usuarios WHERE codigoUser = :codigo");
        $query->bindParam(":codigo", $codigo);
        $query->execute();
        $resultado =  $query->fetch(PDO::FETCH_ASSOC);
        //Instanciamos un novo usuario

        $user = new Users($resultado['rol'], $resultado['userLogin'], $resultado['contrasinal'], $resultado['nome'], $resultado['enderezo'], $resultado['email']);
        //Devolvemos o obxeto usuarios.   
        $user->setCodigo($resultado['codigoUser']);

        var_dump($user);
        return $user;
    }

   


    /*

----------------------------------------------------------------------------------------------
                            METODOS PARA CSV
----------------------------------------------------------------------------------------------

*/

    /*
  -------------------------------------- ENCRIPTAR CONTRASEÑA ----------------------------------------
 */

    public static function codificar($pass)
    {
        //Generamos un salt a través de la función password_hash.
        //Recibe como parametro la contraseña y el tipo de hash que usará 
        $passH = password_hash($pass, PASSWORD_DEFAULT);
        //Devolvemos la contraseña encriptada con Crypt mediante el uso de la contraseña y el salt.
        return crypt($pass, $passH);
    }

    /*
  -------------------------------------- VALIDAR TEXTO ----------------------------------------
 */

    public static function validarTexto($texto)
    {
        //Comprueba que se introduzcan letras de la A-Z sin diferenciar mayuscuas y minusculas y números.
        //Devuelve 1 o 0
        return preg_match("/^[a-z0-9 .\-]+$/i", $texto);
    }

    /*
  -------------------------------------- VALIDAR CONTRASEÑA ----------------------------------------
 */

    public static function validarPass($pass)
    {
        //Expresión regular para verificar la contraseña.
        //Devuelve true or false
        /*
      La contraseña debe tener al entre 8 y 16 caracteres, al menos un dígito, al menos una minúscula y al menos una mayúscula.
      Puede tener otros símbolos.
     */
        return preg_match("^(?=\w*\d)(?=\w*[A-Z])(?=\w*[a-z])\S{8,16}$^", $pass);
    }

    /*
  -------------------------------------- ERROR ----------------------------------------
 */

    function erro($error)
    {
        //confirmamos que el parametro no esté vacío y eliminamos los posibles espacios en blanco.
        if (!empty(trim($error))) {
            echo '<br/><span class ="error" > ' . $error . ' </span>';
        }
    }

    /*
  -------------------------------------- COOKIES ------------------------------------------
 */

    //Recibe el valor establecido en $_POST
    function styleCookie($valor)
    {
        $style = "";
        //Según el valor de $_POST establece la ubicación del archivo CSS que contiene el tema escogido.
        if (strcasecmp($valor, "white") == 0) {
            $style = "./estilos/style.css";
        } else {
            $style = "./estilos/dark.css";
        }
        return $style;
    }

    /*
  -------------------------------------- VALIDAR EMAIL ------------------------------------------
 */

    public static function validarEmail($correo)
    {
        return filter_var($correo, FILTER_VALIDATE_EMAIL);
    }



    /*
  -------------------------------------- BORRAR Cesta ----------------------------------------
 */

    //Recibe una ID y un array de objetos.
    function borrarCesta($id, $array)
    {
        //Realizamos un Unset de la linea correspondiente al valor del ID recibido.
        unset($array[$id]);

        return $array;
    }
}
