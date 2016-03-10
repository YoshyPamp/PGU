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
class Asignatura {
    private $codigo;
    private $seccion;
    private $nombre;
    private $profesor;
    private $año;
    private $semestre;
    private $alumnos;
    
    public function __construct(){
        $this->codigo = 1;
        $this->seccion = 1;
        $this->nombre = "";
        $this->profesor = "";
        $this->año = 1999;
        $this->semestre = 1;
        $this->alumnos = array();
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
    
    public function getCodigo() {
        return $this->codigo;
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function getSeccion() {
        return $this->seccion;
    }

    public function setSeccion($seccion) {
        $this->seccion = $seccion;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getProfesor() {
        return $this->profesor;
    }

    public function setProfesor($profesor) {
        $this->profesor = $profesor;
    }

    public function getAño() {
        return $this->año;
    }

    public function setAño($año) {
        $this->año = $año;
    }

    public function getSemestre() {
        return $this->semestre;
    }

    public function setSemestre($semestre) {
        $this->semestre = $semestre;
    }

    public function getAlumnos() {
        return $this->alumnos;
    }

    public function setAlumnos($alumnos) {
        $this->alumnos = $alumnos;
    }


 
}
