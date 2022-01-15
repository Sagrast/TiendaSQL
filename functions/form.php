<?php

/*
 * @author Oscar González Martínez
 * @version 1.0
 * @date 07/11/2021
 * Esta función esta creada porque entendí que ambos formularios (creación de usuario y registro de usuario)
 * comparten validaciones. Solo se repiten las validaciones que consideré comunes.
 */
include_once "./class/DAO.php";
include_once "./class/Users.class.php";

//Creamos una función formulario para unificar rexistro.php y usuarios.php
//Recibe los parametros de creación de formulario.
//El campo Database corresponde con el archivo CSV almacenado en un array.
function form($rol, $login, $contrasinal, $nomeCompleto, $direccion, $email) {
    $rolOK = $loginOK = $contrasinalOK = $nomeCompletoOK = $direccionOK = $emailOK = "";
    $loginError = $contrasinalError = $nomeCompletoError = $enderezoError = $emailError = "";
    $erros = array();
    

    /*
     * -------------------------------- ROL -------------------------------------
     */
        //el valor del Rol viene controlado desde el formulario.
        $rolOK = $rol;
    /*
     * -------------------------------- LOGIN -------------------------------------
     */
    if (DAO::validarTexto($login) == 1) {
        //Si el texto es correcto y el usuario no existe en la base de datos
        //damos por valido el campo.
        if (!DAO::validateUserBDD($login)) {
            $loginOK = $login;
        } else {
            //En caso contrario, generamos un error y lo añadimos al array.
            $loginError = "O usuario xa existe";
            array_push($erros, $loginError);
        }
    } else {
        $loginError = "Hai carácteres incorrectos.";
        array_push($erros, $loginError);
    }

    /*
     * -------------------------------- Contrasinal -------------------------------------
     */
    
    if (DAO::validarPass($contrasinal)) {
        //Si todo es correcto, ciframos la contraseña
        $contrasinalOK = DAO::codificar($contrasinal);
    } else {
        $contrasinalError = "Contrasinal incorrecto";
        array_push($erros, $contrasinalError);
    }

    /*
     * -------------------------------- Nome Completo -------------------------------------
     */


    if (DAO::validarTexto($nomeCompleto)) {
        $nomeCompletoOK = $nomeCompleto;
    } else {
        //en caso contrario, generamos el error y lo almacenamos en el array.
        $nomeCompletoError = "Valores incorrectos";
        array_push($erros, $nomeCompletoError);
    }
    /*
     * -------------------------------- Direccion -------------------------------------
     */

    //Si el campo no está vacío y cumple los requisitos almacenamos su valor.
    if (DAO::validarTexto($direccion)) {
        $direccionOK = $direccion;
    } else {
        //en caso contrario, generamos el error y lo almacenamos en el array.
        $enderezoError = "Carácteres inválidos na direccion";
        array_push($erros, $enderezoError);
    }
    /*
     * -------------------------------- Email -------------------------------------
     */
    //Si el campo Email es correcto almacenamos su valor.
    if (DAO::validarEmail($email)) {
        $emailOK = $email;
    } else {
        //De lo contrario generamos los errores correspondientes y lo enviamos al array de errores.
        $emailError = "Introduza un correo valido";
        array_push($erros, $emailError);
    }
    
//Si el array de errores está vacío
    if (empty($erros)) {
        //Genereamos un nuevo objeto Usuario que recibe los valores validados previamente.
        $newUser = new Users($rolOK, $loginOK, $contrasinalOK, $nomeCompletoOK, $direccionOK, $emailOK);        
        //lo añadimos al array de base de datos.        
        //Escribimos el array en el el archivo correspondiente.
        DAO::escribirUsuariosBDD($newUser);
        header("location: perfil.php");
    } else {
        //Si el array contiene errores, los mostramos por pantalla.
        foreach ($erros as $errores){
            echo DAO::erro($errores);
        }
    }
}

?>