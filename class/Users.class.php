<?php

/**
 * Clase Usuario
 *
 * @author Oscar González Martínez
 * Versión 1.0
 */
class Users {

    private $rol;
    private $login;
    private $contrasinal;
    private $nome;
    private $enderezo;
    private $email;

    public function __construct($rol, $login, $contrasinal, $nome, $enderezo, $email) {
        $this->rol = $rol;
        $this->login = $login;
        $this->contrasinal = $contrasinal;
        $this->nome = $nome;
        $this->enderezo = $enderezo;
        $this->email = $email;
    }

    // ---- GETTER -----//
    public function getNome() {
        return $this->nome;
    }

    public function getEnderezo() {
        return $this->enderezo;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getLogin() {
        return $this->login;
    }

    public function getContrasinal() {
        return $this->contrasinal;
    }

    public function getRol() {
        return $this->rol;
    }

    //--------------- SETTER ---------------------

    public function setLogin($login): void {
        $this->login = $login;
    }

    public function setContrasinal($contrasinal): void {
        $this->contrasinal = $contrasinal;
    }

    public function setRol($rol): void {
        $this->rol = $rol;
    }

    public function setNome($nome): void {
        $this->nome = $nome;
    }

    public function setEnderezo($enderezo): void {
        $this->enderezo = $enderezo;
    }

    public function setEmail($email): void {
        $this->email = $email;
    }

    

}

?>