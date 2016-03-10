<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Plan
 *
 * @author joshe.onate
 */
class Plan {
    
    private $tipo_plan;
    private $facultad;
    private $escuela;
    private $sede;
    private $grado_bach;
    private $grado_acad;
    private $titulo;
    private $nombre;
    private $codigo;
    private $jornada;
    private $duracion_sem;
    
    function __construct() {
        $this->tipo_plan = "";
        $this->facultad = "";
        $this->escuela = "";
        $this->sede = "";
        $this->grado_bach = "";
        $this->grado_acad = "";
        $this->titulo = "";
        $this->nombre = "";
        $this->codigo = "";
        $this->jornada = "";
        $this->duracion_sem = "";
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
    
    function getTipo_plan() {
        return $this->tipo_plan;
    }
    
    function getFacultad() {
        return $this->facultad;
    }

    function getEscuela() {
        return $this->escuela;
    }

    function getSede() {
        return $this->sede;
    }

    function getGrado_bach() {
        return $this->grado_bach;
    }

    function getGrado_acad() {
        return $this->grado_acad;
    }

    function getTitulo() {
        return $this->titulo;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getCodigo() {
        return $this->codigo;
    }

    function getJornada() {
        return $this->jornada;
    }

    function getDuracion_sem() {
        return $this->duracion_sem;
    }

    function setTipo_plan($tipo_plan) {
        $this->tipo_plan = $tipo_plan;
    }
    
    function setFacultad($facultad) {
        $this->facultad = $facultad;
    }

    function setEscuela($escuela) {
        $this->escuela = $escuela;
    }

    function setSede($sede) {
        $this->sede = $sede;
    }

    function setGrado_bach($grado_bach) {
        $this->grado_bach = $grado_bach;
    }

    function setGrado_acad($grado_acad) {
        $this->grado_acad = $grado_acad;
    }

    function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    function setJornada($jornada) {
        $this->jornada = $jornada;
    }

    function setDuracion_sem($duracion_sem) {
        $this->duracion_sem = $duracion_sem;
    }


}
