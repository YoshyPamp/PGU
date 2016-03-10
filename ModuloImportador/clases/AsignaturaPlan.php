<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AsignaturaPlan
 *
 * @author joshe.onate
 */
class AsignaturaPlan {
    
    private $nivel;
    private $codigo;
    private $nombre;
    
    function __construct() {
        $this->nivel = "";
        $this->codigo = "";
        $this->nombre = "";
    }
    
    function getNivel() {
        return $this->nivel;
    }

    function getCodigo() {
        return $this->codigo;
    }

    function getNombre() {
        return $this->nombre;
    }

    function setNivel($nivel) {
        $this->nivel = $nivel;
    }

    function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }


    

}
