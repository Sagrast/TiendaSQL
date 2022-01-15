<?php
include_once "./class/Users.class.php";
include_once "./class/Produtos.class.php";
include_once "./class/Connect.class.php";
class DAO {


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


public static function productsBDD(){
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
    if (!$select){
        //Generamos un error.
        $DATA['Error']= "Error de Consulta de Conexión";
    } else {
        //Si es correcta recorremos cada fila de la consulta usando la propiedad de PDO de array asociativo, creando así un array
        //Cuyo indice será el nombre de la columna y valor el contenido de la fila correspondiente a la columna.
        while ($row = $select->fetch(PDO::FETCH_ASSOC)){                                    
            $data[] = $row;            
            
        }
        
    }
    //Usando el array asociativo anterior instanciamos objetos de la clase Productos para poder así user los metodos propios de esta clase.
    foreach($data as $d){        
        $producto = new Produtos($d['nome'],$d['descricion'],$d['unidades'],$d['prezo'],$d['fotos'],$d['ive']);
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

public static function userBDD() {
    $conexion = new Connect();
    $con = $conexion->conexion();
    $select = $con->prepare('SELECT * FROM usuarios');
    $select->execute();
    $data = array();
    $resultado = array();
    if (!$select){
        $DATA['Error'] = "Error de Consulta de Conexión";
    } else {
        while ($row = $select->fetch(PDO::FETCH_ASSOC)){
            $data[] = $row;
        }
    }
    foreach($data as $d){
        $usuario = new Users($d['rol'],$d['userLogin'],$d['contrasinal'],$d['nome'],$d['enderezo'],$d['email']);
        $usuario->setCodigo($d['codigoUser']);
        $resultado[] = $usuario;
    }
   
    return $resultado;

}
/*
  -------------------------------------- VALIDAR USUARIO ----------------------------------------
 */

public static function validateUserBDD($login) { 
    //Abrimos conexion
    $conexion = new Connect();
    $con = $conexion->conexion();
    //Se prepara la consulta
    $query = $con->prepare('SELECT userLogin FROM usuarios WHERE userLogin = :userLogin');
    //Enlazmos parametros
    $query->bindParam(":userLogin",$login);
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
public static function deleteBDD($id) {
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
    $delete->bindParam(":codigo",$id);
    //Se ejecuta la consulta y si el resultado es igual a 0 cambiamos la variable OK a falsa.
    if ($delete->execute() == 0) {
        $ok = false;
    }
    
    //Si todo ha ido bien $ok sigue en True, se confirma la transacción. De lo contrario revertimos los cambios.
    if ($ok){        
        $con->commit();
    } else {
        $con->rollBack();
    }
    //$delete->execute();    
}
/*
  -------------------------------------- Escribir BDD ----------------------------------------
 */

public static function escribirUsuariosBDD($datos) {
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
    $insert->bindParam(':rol',$rol);
    $insert->bindParam(':userLogin',$login);
    $insert->bindParam(':contrasinal',$pass);
    $insert->bindParam(':nome',$nome);
    $insert->bindParam(':enderezo',$enderezo);
    $insert->bindParam(':email', $mail);
     if ($insert->execute() == 0) {
         $ok = false;
     }

     if ($ok) {
         $con->commit();
     } else {
         $con->rollBack();
     }
   
    return true;
}

/*
  -------------------------------------- COMPARAR HASH BDD------------------------------------------
 */ 

public static function comparaHashBDD($user, $pass) {
    //Como siempre, abrimos conexión a MySQL
    $conexion = new Connect();
    $con = $conexion->conexion();
    //Preparamos la consulta
    $query = $con->prepare("SELECT contrasinal FROM usuarios WHERE userLogin = :userLogin");
    //Vinculamos el parametro recibido a la consulta. Y ejecutamos.
    $query->bindParam(":userLogin",$user);
    $query->execute();
    //Almacenamos el array que devuelve.
    $cont = $query->fetch(PDO::FETCH_ASSOC);
    //Y nos quedamos con el valor que necesitamos en una variable, pues Crypt da error si recibe un array en vez de el String.
    $contrasinal = $cont['contrasinal'];
    //Inicializamos un booleano que será la respuesta de la función.    
    $existe = false;   
    //Pasamos los datos por la función Hash Equals con un Crypt y nos devolverá verdadero si ambos hash son correctos.
            if (hash_equals($contrasinal, crypt($pass,$contrasinal))) {
                $existe = true;
            }    
    return $existe;
    
}

/*
  -------------------------------------- COMPROBAR ROL DE USUARIO CON BDD ------------------------------------------
 */

public static function esAdminBDD($usuario) {
    //Iniciamos una variable a falso.    
    $admin = false;
    //Preparamos la conexión, Query y asociamos parametros.
    $conexion = new Connect();
    $con = $conexion->conexion();
    $query = $con->prepare("SELECT rol FROM usuarios WHERE userLogin = :userLogin");
    $query->bindParam(":userLogin",$usuario);
    $query->execute();
    //almacenamos el resultado en un array.
    $rol = $query->fetch(PDO::FETCH_ASSOC);
    //Hacemos una comparación del valor del array con la cadena 'Administrador', si es correcto devolvemos verdadero.
        if (strcasecmp($rol['rol'],'Administrador') == 0){
            $admin = true;
        }
    
    return $admin;
}




/*

----------------------------------------------------------------------------------------------
                            METODOS PARA CSV
----------------------------------------------------------------------------------------------

*/

/*
  -------------------------------------- LEER CSV ----------------------------------------

 */


function leerProdutos($ruta) {
    $fichero = "./csv/$ruta";
    $arrayDatos = array();
    if ($fp = fopen($fichero, "r")) {
        while ($fileDatos = fgetcsv($fp, 0, ",")) {
            $produto = new Produtos($fileDatos[0], $fileDatos[1], $fileDatos[2], $fileDatos[3], $fileDatos[4],$fileDatos[5],$fileDatos[6]);
            $arrayDatos[] = $produto;
        }
    } else {
        echo "Erro! Non se pode acceder ao ficheiro: " . $fichero . "<br>";
        return false;
    }
    fclose($fp);
    return $arrayDatos;
}


/*
  --------------------- LEER USUARIOS --------------------------------------------
 */

function leerUsuarios($ruta) {
    $fichero = "./csv/$ruta";
    $arrayDatos = array();
    if ($fp = fopen($fichero, "r")) {
        while ($fileDatos = fgetcsv($fp, 0, ",")) {
            $usuario = new Users($fileDatos[0], $fileDatos[1], $fileDatos[2], $fileDatos[3], $fileDatos[4], $fileDatos[5]);
            $arrayDatos[] = $usuario;
        }
    } else {
        echo "Erro! Non se pode acceder ao ficheiro: " . $fichero . "<br>";
        return false;
    }
    fclose($fp);
    return $arrayDatos;
}


/*
  -------------------------------------- ENCRIPTAR CONTRASEÑA ----------------------------------------
 */

function codificar($pass) {
    //Generamos un salt a través de la función password_hash.
    //Recibe como parametro la contraseña y el tipo de hash que usará 
    $passH = password_hash($pass, PASSWORD_DEFAULT);
    //Devolvemos la contraseña encriptada con Crypt mediante el uso de la contraseña y el salt.
    return crypt($pass, $passH);
}

/*
  -------------------------------------- VALIDAR TEXTO ----------------------------------------
 */

function validarTexto($texto) {
    //Comprueba que se introduzcan letras de la A-Z sin diferenciar mayuscuas y minusculas y números.
    //Devuelve 1 o 0
    return preg_match("/^[a-z0-9 .\-]+$/i", $texto);
}

/*
  -------------------------------------- VALIDAR CONTRASEÑA ----------------------------------------
 */

function validarPass($pass) {
    //Expresión regular para verificar la contraseña.
    //Devuelve true or false
    /*
     * La contraseña debe tener al entre 8 y 16 caracteres, al menos un dígito, al menos una minúscula y al menos una mayúscula.
      Puede tener otros símbolos.
     */
    return preg_match("^(?=\w*\d)(?=\w*[A-Z])(?=\w*[a-z])\S{8,16}$^", $pass);
}

/*
  -------------------------------------- VALIDAR USUARIO ----------------------------------------
 */

function validarUsuario($user, $users) {
    //Recibe un usuario a buscar y un array de objetos creado a partir del csv.
    //Busca si existe el usuario en el CSV
    //Inicializamos una variable a falso.
    $existe = false;
    //Recorremos el array.
    foreach ($users as $usuarios) {
        //Comparamos el usuario recibido con el la propiedad login del objeto usuario recibido.
        if (strcasecmp($usuarios->getLogin(), $user) == 0) {
            $existe = true;
        }
    }
    //Devolvemos la variable existe.
    return $existe;
}

/*
  -------------------------------------- ERROR ----------------------------------------
 */

function erro($error) {
    //confirmamos que el parametro no esté vacío y eliminamos los posibles espacios en blanco.
    if (!empty(trim($error))) {
        echo '<br/><span class ="error" > ' . $error . ' </span>';
    }
}

/*
  -------------------------------------- AÑADIR CSV ----------------------------------------
 */

function añadirCSV($ruta, $campos) {

    $fichero = "./csv/$ruta";
    //abrimos el fichero en modo append: Apertura para sólo escritura; coloca el puntero del fichero al final del mismo. Si el fichero no existe, se intenta crear.
    // En este modo, fseek() solamente afecta a la posición de lectura; las lecturas siempre son pospuestas.
    $fileW = fopen($fichero, "a");

    //escribimos con la función fputcsv el array listado en el fichero abierto.
    fputcsv($fileW, $campos);

    //Cerramos el fichero.
    fclose($fileW);
}

/*
  -------------------------------------- BORRAR USUARIO ----------------------------------------
 */

//Recibe una ID y un array de objetos.
function borrarUsuario($id, $array) {
    //Realizamos un Unset de la linea correspondiente al valor del ID recibido.
    unset($array[$id]);

    return $array;
}

/*
  -------------------------------------- Escribir CSV ----------------------------------------



 */

function escribirUsuarios($ruta, $datos) {
    $fichero = "./csv/$ruta";
    if ($fp = fopen($fichero, "w")) {
        foreach ($datos as $usuario) {
            $filaDatos = [$usuario->getRol(), $usuario->getLogin(), $usuario->getContrasinal(), $usuario->getNome(), $usuario->getEnderezo(), $usuario->getEmail()];
            fputcsv($fp, $filaDatos);
        }
    } else {
        echo "ERRO! Non se pode acceder ao ficheiro: " . $fichero . "<br/>";
        return false;
    }
    fclose($fp);
    return true;
}

/*
  -------------------------------------- COMPARAR HASH v2------------------------------------------
 */

//Función CompararHash
//Recibe 3 parametros: Usuario, Contraseña y Array del CSV  

function comparaHash($user, $pass, $datos) {

    //Si inicializa una variable existe a falso.
    $existe = false;
    //Inicializamos un contador a 0
    $i = 0;
    //Mientras existe sea falso y el contador inferior al tamaño del array
    while ((!$existe) && ($i < count($datos))) {
        //Asignamos en dos variables el login y la contraseñal de la iteración del bucle.
        $login = $datos[$i]->getLogin();
        $password = $datos[$i]->getContrasinal();
        //Comparamos el campo de usuarios ignorando Mayusculas/Minusculas.
        //Con el valor almacenado en la variable $login
        if (strcasecmp($login, $user) == 0) {
            //Si se encuentra el usuario, comparamos los Hash de las contraseñas.
            //realizamos un hashEquals de la contraseña almacenada en la variable Password
            //con la salida de la función Crypt que recibe como parametros la contraseña introducida en el login
            //Y la variable que contiene el hash del usuario.
            if (hash_equals($password, crypt($pass, $password))) {
                $existe = true;
            }
        }
        //Aumentamos el valor de I
        $i++;
    }
    return $existe;
}

/*
  -------------------------------------- COMPROBAR ROL DE USUARIO ------------------------------------------
 */

function esAdmin($usuario, $datos) {
    //Iniciamos una variable a falso.    
    $admin = false;
    //Iniciamos un contador a 0
    $i = 0;
    //Mienntras admin sea falso y el contador inferior al tamño del array
    while ((!$admin) && ( $i < count($datos))) {
        //Almacenamos en una variable la propiedad login del objeto actual del array.
        $login = $datos[$i]->getLogin();
        $rol = $datos[$i]->getRol();
        //Comparamos el login con el usuario recibido.
        if (strcasecmp($login, $usuario) == 0) {
            //Si existe, comparamos el valor de su celda con la cadena administrador.
            if (strcasecmp($rol, 'Administrador') == 0) {
                $admin = true;
            }
        }
        //Aumentamos el contador.
        $i++;
    }
    return $admin;
}

/*
  -------------------------------------- COOKIES ------------------------------------------
 */

//Recibe el valor establecido en $_POST
function styleCookie($valor) {
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

function validarEmail($correo) {
    return filter_var($correo, FILTER_VALIDATE_EMAIL);
}

/*
  ----------------------------------------- Buscar Producto ---------------------------------

 */

function buscarProducto($codigo) {
    $stock = DAO::leerProdutos("productos.csv");
    $existe = false;
    $i = 0;
    while ($existe = false && $i < count($stock)) {
        if ($codigo == $this->producto[$i]) {
            $existe = true;
            return "El articulo ya está en la cesta";
        } else {
            $i++;
        }
    }
    return $existe;
}

/*
  ----------------------------------------- Escribir Cesta ---------------------------------
 */

function escribirCesta($cesta,$usuario) {
    $fichero = "./csv/cesta.csv";
    $fecha = date("m-d-y");    
    if ($fp = fopen($fichero, "a")) {
        foreach ($cesta->getProducto() as $indice => $producto) {
            $venta = [$fecha ,   $indice ,  $producto, $usuario];
            fputcsv($fp, $venta);
        }
    }
    fclose($fp);
}

/*
 * ----------------------------------------- Escribir Cesta --------------------------------- 
 */

function escribirProductos($productos){
    $fichero = "./csv/productos.csv";
    var_dump($productos);
    if ($fp = fopen($fichero, "a")) {
        foreach ($productos as $producto) {
            $filaDatos = [$producto->getCodigo(),$producto->getNome(),$producto->getDescricion(),$producto->getUnidades(),$producto->getPrezo(),$producto->getFotos(),$producto->getIve()];
            fputcsv($fp, $filaDatos);
        }
    } else {
        echo "ERRO! Non se pode acceder ao ficheiro: " . $fichero . "<br/>";
        return false;
    }
    fclose($fp);
    return true;
}

/* 
* ----------------------------------------- Comprobar código --------------------------------- 
 */
//Recibe el array de productos y el código a buscar.

function findCodeProd($stock,$code){
    //inicia un booleano a falso.
    $exists = false;
    foreach ($stock as $prod){
        if ($code == $prod->getCodigo()){
            //si el codigo existe, el booleano pasa a verdadero.
            $exists = true;
        }
    }
    //devuelve el valor booleano
    return $exists;
}
    
/*
  -------------------------------------- BORRAR Cesta ----------------------------------------
 */

//Recibe una ID y un array de objetos.
function borrarCesta($id, $array) {
    //Realizamos un Unset de la linea correspondiente al valor del ID recibido.
    unset($array[$id]);

    return $array;
}
    
    
}
