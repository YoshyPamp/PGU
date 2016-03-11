<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Database
 *
 * @author YoshyPamp
 */
class Database {
    
    private $DB_NAME;
    private $DB_USER;
    private $DB_PASS;
    private $DB_SERVER;
    private $connectionInfo;
    public $conn;
    
    function __construct() {
	//PRD
	//$this->DB_NAME = "FAM";
    //    $this->DB_USER = "sa";
    //    $this->DB_PASS = "informatica.2015*";
    //    $this->DB_SERVER = "172.16.22.8";

	//QAS
        $this->DB_NAME = "FAM";
        $this->DB_USER = "valentys_sql";
        $this->DB_PASS = "valentys.2012*";
        $this->DB_SERVER = "172.16.39.64";
        $this->connectionInfo = array("Database" => $this->DB_NAME,
                                      "UID" => $this->DB_USER,
                                      "PWD" => $this->DB_PASS);
        $this->conection();
    }
    
    function conection(){
        $this->conn = odbc_connect("FAM",
                $this->DB_USER, $this->DB_PASS);
        if( $this->conn ) {
            
        }else{
            echo "Conexión no se pudo establecer.<br />";
            die(print_r( odbc_error(), true));
        }
    }
    
    function select_alumnos(){
        
        $result = array();
        $dec = 'SELECT * FROM ALUMNO';
        $stmt = odbc_exec($this->conn,$dec);

        if( $stmt === false) {
            die( print_r( odbc_error(), true) );
        }
        
        while( $row = odbc_fetch_array($stmt) ) {
            $result[]['RUT'] = $row['RUT'];
            $result[count($result)-1]['N_MATRICULA'] = $row['N_MATRICULA'];
            $result[count($result)-1]['NOMBRES'] = utf8_encode($row['NOMBRES']);
            $result[count($result)-1]['REGIMEN'] = $row['REGIMEN'];
            $result[count($result)-1]['PLAN_ESTUDIO'] = utf8_encode($row['PLAN_ESTUDIO']);
            $result[count($result)-1]['CODIGO_PLAN'] = $row['CODIGO_PLAN'];
            $result[count($result)-1]['ESTADO_ESTUDIO'] = $row['ESTADO_ESTUDIO'];
        }
        odbc_free_result( $stmt);
        
        return $result;
    }
    
    function select_asignaturas(){
        
        $result = array();
        
        $dec = 'SELECT * FROM dbo.ASIGNATURA ORDER BY NOM_ASIGNATURA';
        $stmt = odbc_exec($this->conn,$dec);
        
        if( $stmt === false) {
            die( print_r( odbc_error(), true) );
        }
        
        while( $row = odbc_fetch_array($stmt) ) {
            $result[]['COD_ASIGNATURA'] = $row['COD_ASIGNATURA'];
            $result[count($result)-1]['NOM_ASIGNATURA'] = utf8_encode($row['NOM_ASIGNATURA']);
            $result[count($result)-1]['PLANESTUDIO_COD_PLANESTUDIO'] = $row['PLANESTUDIO_COD_PLANESTUDIO'];
        }
        odbc_free_result( $stmt);
        
        return $result;
    }
    
    function select_alumno_rut($rut){
        $result = array();
        
        $dec = "execute select_alumno_rut @rut = '".$rut."'";
        $stmt = odbc_exec($this->conn,$dec);
        
        if( $stmt === false )
        {
             echo "Error al ejecutar procedimiento.\n";
             die( print_r( odbc_error(), true));
        }
        
        while( $row = odbc_fetch_array($stmt) ) {
            $result['RUT'] = $row['RUT'];
            $result['N_MATRICULA'] = $row['N_MATRICULA'];
            $result['NOMBRES'] = utf8_encode($row['NOMBRES']);
            $result['REGIMEN'] = $row['REGIMEN'];
            $result['PLAN_ESTUDIO'] = utf8_encode($row['PLAN_ESTUDIO']);
            $result['CODIGO_PLAN'] = $row['CODIGO_PLAN'];
            $result['ESTADO_ESTUDIO'] = $row['ESTADO_ESTUDIO'];
        }
        odbc_free_result( $stmt);
        
        return $result;
    }
	
	function SELECT_ASIGNATURAS_BYPLAN($codigo) {
		
		$deg = "SELECT * FROM ASIGNATURA WHERE PLANESTUDIO_COD_PLANESTUDIO = '".$codigo."'";
		
		$stmt = odbc_exec($this->conn,$deg);
		
		$deg2 = "SELECT DURACION FROM PLANESTUDIO WHERE COD_PLANESTUDIO = '".$codigo."'";
		
		$stmt2 = odbc_exec($this->conn,$deg2);
		
		if( $stmt === false )
        {
             echo "Error al ejecutar procedimiento.\n";
             die( print_r( odbc_error(), true));
        }
		
		if( $stmt2 === false )
        {
             echo "Error al ejecutar procedimiento.\n";
             die( print_r( odbc_error(), true));
        }
		
		$plan = odbc_fetch_array($stmt2);
		$duracion = $plan['DURACION'];
		
		$result = array();
		$con = 1;
		while($con < $duracion){
			$result[$con] = array();
			$con++;
		}

		
		while( $row = odbc_fetch_array($stmt) ) {
			$result[$row['NIVEL']][] = array(
				$row['NOM_ASIGNATURA'],
				$row['COD_ASIGNATURA']
			);
        }
		
        odbc_free_result( $stmt);
        
		$result['DURACION'] = $duracion;
		
        return $result;
		
	}
    
    function FAM_PLAN_INSERT($codigo, $tipo, $facultad, $escuela,
            $sede, $grd_bach, $grd_acad, $titulo, $nombre, $jornada,$duracion){
        
        $dec = "INSERT INTO CARRERA OUTPUT Inserted.COD_CARRERA VALUES('$nombre','$jornada','ICC-01')";
			
		$stmt = odbc_exec($this->conn,$dec);
        
        if( $stmt === false ) {
			echo "Error al ejecutar procedimiento.\n";
			die( print_r( odbc_error(), true));
        }else {
			$row = odbc_fetch_array($stmt);
			$id_carrera = $row['COD_CARRERA'];
		}
        
        odbc_free_result( $stmt);
			
		$dec2 = "INSERT INTO PLANESTUDIO VALUES('$codigo','$id_carrera','$nombre','$tipo',
		'$grd_bach','$grd_acad','$titulo','$duracion')";
		
        $stmt = odbc_exec($this->conn,$dec2);
        
        if( $stmt === false ) {
			echo "Error al ejecutar procedimiento.\n";
			die( print_r( odbc_error(), true));
        }
		
		odbc_free_result( $stmt);
    }  
	
	function FAM_ASIGNATURA_INSERT($codigo_plan, $nombre, $nivel, $codigo){
        
        $dec = "INSERT INTO ASIGNATURA VALUES("
                . "'".$codigo."',"
                . "'".$nombre."',"
                . "'".$codigo_plan."',"
				.$nivel.")";
				
        $stmt = odbc_exec($this->conn,$dec);
        
        if( $stmt === false )
        {
             echo "Error al ejecutar procedimiento.\n";
             die( print_r( odbc_error(), true));
        }
        
        odbc_free_result( $stmt);
    }  
	
	function VERIFICAR_PLAN_EXISTENTE($codigo){
		
		$deg = "SELECT * FROM PLANESTUDIO WHERE COD_PLANESTUDIO = '".$codigo."'";
		
		$stmt = odbc_exec($this->conn,$deg);
		
		if( $stmt === false )
        {
             echo "Error al ejecutar procedimiento.\n";
             die( print_r( odbc_error(), true));
        }
		
		if(odbc_num_rows($stmt) == 0){
			return false;
		}else{
			return true;
		}
		
		odbc_free_result( $stmt);
	}
}

?>

