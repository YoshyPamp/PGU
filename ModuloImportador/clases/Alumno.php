<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Alumno
 *
 * @author YoshyPamp
 */
class Alumno {
    private $nombre;
    private $apellidoPaterno;
    private $apellidoMaterno;
    private $rut;
    private $plan;
    
    public function __construct(){
        $this->nombre = "";
        $this->rut = "";
        $this->plan = "";
    }

    public function getNombre() {return $this->nombre;}
    public function getApellidoPaterno() {return $this->apellidoPaterno;}
    public function getApellidoMaterno() {return $this->apellidoMaterno;}
    public function getRut() {return $this->rut;}
    public function getPlan() {return $this->plan;}
    
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
    
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setApellidoPaterno($apellidoPaterno) {
        $this->apellidoPaterno = $apellidoPaterno;
    }

    public function setApellidoMaterno($apellidoMaterno) {
        $this->apellidoMaterno = $apellidoMaterno;
    }

    public function setRut($rut) {
        $this->rut = $rut;
    }

    public function setPlan($plan) {
        $this->plan = $plan;
    }


}
