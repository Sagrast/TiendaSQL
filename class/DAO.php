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

------------------------------------ LEER BDD -------------------------------------------


*/

public static function leerProdutosBDD(){    
    $tenda = new PDO('mysql:host=localhost;dbname=tienda',"phpmyadmin","abc123.");
    $select = $tenda->query('Select * from productos');
    $data = array();
    if (!$select){
        $DATA['Error']= "Error de Consulta de Conexión";
    } else {
        while ($row = $select->fetch(PDO::FETCH_ASSOC)){
            $data[] = $row;
        }
    }
    var_dump($data);
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
?>