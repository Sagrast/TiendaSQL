<?php

/* 
    @author: Oscar González Martínez
 *  @version: 1.0
 *  Fecha: 8/11/2021
 *  */


class Cesta {
    //put your code here
    private $producto;
    
    public function __construct() {
        $this->producto = array();
    }
    
    
    public function getProducto() {
        return $this->producto;
    }
    
    
    
   /* 
    public function engadirArtigo($codigo,$unidades){
        $stock = leerProdutos("productos.csv");
        $existe = false;
        $i = 0;
    while ($existe = false && $i < count($stock)) {
        if ($codigo == $this->producto[$i]) {
            $existe = true;
            $this->producto = $this->producto + $unidades;
        } else {
            $this->producto[$codigo] = $unidades;
        }
    }
    return $existe;
         
    }
    * 
    */
    
    public function engadirArtigo($codigo){        
        if (isset($this->producto[$codigo])){
            $this->producto[$codigo]+=1;
        } else {
            $this->producto[$codigo] = 1;
    }   
         
    }
    
    public function eliminarArtigo($codigo){
        if (isset($this->producto[$codigo]) && $this->producto[$codigo] > 0){
            $this->producto[$codigo]-=1;
        } else {
            $this->producto[$codigo] = 0;
    }
    }
    public function amosar(){
        return var_dump($this->producto);
    }
    

}
