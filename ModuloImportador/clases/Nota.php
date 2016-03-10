<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Nota
 *
 * @author joshe.onate
 */
class Nota {
    
    private $nota;
    private $porcentaje;
    private $nota_ponderada;
    private $tipo;
    
    function __construct() {
        $this->nota = 1.0;
        $this->porcentaje = "10%";
        $this->nota_ponderada = 1.0;
        $this->tipo = "";
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }
    
    public function getNota() {
        return $this->nota;
    }

    public function setNota($nota) {
        $this->nota = $nota;
    }

    public function getPorcentaje() {
        return $this->porcentaje;
    }

    public function setPorcentaje($porcentaje) {
        $this->porcentaje = $porcentaje;
    }

    public function getNota_ponderada() {
        return $this->nota_ponderada;
    }

    public function setNota_ponderada($nota_ponderada) {
        $this->nota_ponderada = $nota_ponderada;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }


}

?>
