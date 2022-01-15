<?php
/**
 * Description of Pordutos
 *
 * @author Oscar González Martínez
 * @version 1.0
 * Fecha: 06/11/2021
 */
class Produtos {
    private $codigo;
    private $nome;
    private $descricion;
    private $unidades;
    private $prezo;
    private $fotos;
    private $ive;
//Dado que el código de usuario ahora se genera de forma Auto Incremental cuando se inserta un articulo en la bdd
    //Se retira la exigencia de este en el constructor y se le da valor a través del Setter cuando sea necesario.
//CONSTRUCTOR    
    public function __construct($nome, $descricion, $unidades, $prezo,$fotos,$ive) {        
        $this->nome = $nome;
        $this->descricion = $descricion;
        $this->unidades = $unidades;
        $this->prezo = $prezo;
        $this->fotos = $fotos;
        $this->ive = $ive;
    }

//GETTER    
    public function getCodigo() {
        return $this->codigo;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getDescricion() {
        return $this->descricion;
    }

    public function getUnidades() {
        return $this->unidades;
    }

    public function getPrezo() {
        return $this->prezo;
    }

    public function getFotos() {
        return $this->fotos;
    }
    public function getIve() {
        return $this->ive;
    }


//Setter
    public function setCodigo($codigo): void {
        $this->codigo = $codigo;
    }

    public function setNome($nome): void {
        $this->nome = $nome;
    }

    public function setDescricion($descricion): void {
        $this->descricion = $descricion;
    }

    public function setUnidades($unidades): void {
        $this->unidades = $unidades;
    }

    public function setPrezo($prezo): void {
        $this->prezo = $prezo;
    }

    public function setFotos($fotos): void {
        $this->fotos = $fotos;
    }
    public function setIve($ive): void {
        $this->ive = $ive;
    }



//Funciones
    
    
    public function prezoConIVE(){
        switch ($this->ive){
            case 4:
                return $this->prezo * 1.04;
                break;
            case 10:
                return $this->prezo * 1.10;
                break;
            case 21:
                return $this->prezo * 1.21;
                break;
        }
    }
    
    //Recibe un valor entero. Si el resultado de la resta es mayor o igual a 0
    //se realiza la venta. Si no, avisa de que no hay stock disponible.
    public function mercar($cantidade){
        if (($this->unidades-=$cantidade) >= 0){
            $this->unidades-=$cantidade;
        } else {
            echo "Non hai unidades suficientes en Stock";
        }
    }
    
    //Recibe un valor entero Suma el valor a las unidades del producto.
    public function reponher($cantidade){
        $this->unidades+=$cantidade;
    }
    
    //funcion to.String.
    public function amosarProducto(){
        echo "Codigo: ".$this->codigo." Nome: ".$this->nome." Descricion: ".$this->descricion." Prezo: ". $this->prezo. " Unidades: " . $this->unidades;
    }


    
}
