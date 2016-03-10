<?php





class OfertaAsignatura{
	
	private $nombre;
	private $codigo;
	private $seccion;
	private $profesor;
	private $modalidad;
	private $capacidad;
	private $libres;
	private $inscritos;
	private $inicio;
	private $termino;
	private $dia;
	private $sala;
	
	function __construct() {
		$this->nombre = null;
		$this->codigo = null;
		$this->seccion = null;
		$this->profesor = null;
		$this->modalidad = null;
		$this->capacidad = null;
		$this->libres = null;
		$this->inscritos = null;
		$this->inicio = null;
		$this->termino = null;
		$this->dia = null;
		$this->sala = null;
	}
	
	function getNombre() {
        return $this->nombre;
    }
	
	function setNombre($nombre) {
        $this->nombre = $nombre;
    }
	
	function getCodigo() {
        return $this->codigo;
    }
	
	function setCodigo($codigo) {
        $this->codigo = $codigo;
    }
	
	function getSeccion() {
        return $this->seccion;
    }
	
	function setSeccion($seccion) {
        $this->seccion = $seccion;
    }
	
	function getProfesor() {
		return $this->profesor;
	}
	
	function setProfesor($profesor) {
		$this->profesor = $profesor;
	}
	
	function getModalidad() {
        return $this->modalidad;
    }
	
	function setModalidad($modalidad) {
        $this->modalidad = $modalidad;
    }
	
	function getCapacidad() {
        return $this->capacidad;
    }
	
	function setCapacidad($capacidad) {
        $this->capacidad = $capacidad;
    }
	
	function getLibres() {
        return $this->libres;
    }
	
	function setLibres($libres) {
        $this->libres = $libres;
    }
	
	function getInscritos() {
        return $this->inscritos;
    }
	
	function setInscritos($inscritos) {
        $this->inscritos = $inscritos;
    }
	
	function getInicio() {
        return $this->inicio;
    }
	
	function setInicio($inicio) {
        $this->inicio = $inicio;
    }
	
	function getTermino() {
        return $this->termino;
    }
	
	function setTermino($termino) {
        $this->termino = $termino;
    }
	
	function getDia() {
        return $this->dia;
    }
	
	function setDia($dia) {
        $this->dia = $dia;
    }
	
	function getSala() {
        return $this->sala;
    }
	
	function setSala($sala) {
        $this->sala = $sala;
    }
	
	
}