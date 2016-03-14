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
	
	function VERIFICAR_OFERTA_EXISTENTE($año, $semestre){
		
		$deg = "SELECT * FROM OFERTA WHERE SEMESTRE = ".$semestre." AND ANO = ".$año;
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
	
	function FAM_OFERTA_INSERT($año, $semestre){
		
		$dec = "INSERT INTO OFERTA OUTPUT Inserted.ID_OFERTA VALUES("
                . "'".$año."',"
                . "'".$semestre."')";
				
        $stmt = odbc_exec($this->conn,$dec);
        
        if( $stmt === false )
        {
             echo "Error al ejecutar procedimiento.\n";
             die( print_r( odbc_error(), true));
        }else {
			$row = odbc_fetch_array($stmt);
			$id_oferta = $row['ID_OFERTA'];
		}
        
        odbc_free_result( $stmt);
		
		return $id_oferta;
	}
	
	function FAM_SECCION_INSERT($codigo, $seccion, $profesor, $oferta_id, $inscritos, $cupos, $capacidad, $sala,
			$dia, $inicio, $termino, $modalidad){
		
		/*
		*SELECCIONA ID DE ASIGNATURA SEGUN CODIGO la seccion correspondiente
		*/
		
		$dec = "SELECT ID_ASIGNATURA FROM ASIGNATURA WHERE COD_ASIGNATURA = '".$codigo."'";
		
		$stmt = odbc_exec($this->conn,$dec);
		
		if( $stmt === false )
        {
             echo "Error al ejecutar procedimiento.\n";
             die( print_r( odbc_error(), true));
        }else {
			if(odbc_num_rows($stmt) == 0){
				
				$id_seccion = 0;
				odbc_free_result( $stmt);
			}else{
				$row = odbc_fetch_array($stmt);
				$id_asignatura = $row['ID_ASIGNATURA'];
				odbc_free_result( $stmt);
				
				/*
				*Inserta la seccion correspondiente
				*/
				$dec = "INSERT INTO SECCION OUTPUT Inserted.ID_SECCION VALUES("
						. "'".$codigo.'-'.$seccion."',"
						. "'".$profesor."',"
						. "'".$oferta_id."',"
						. "'".$inscritos."',"
						. "'".$cupos."',"
						. "'".$capacidad."',"
						. "'".$dia."',"
						. "'".$inicio."',"
						. "'".$termino."',"
						. "'".$modalidad."')";
						
				$stmt = odbc_exec($this->conn,$dec);
				
				if( $stmt === false )
				{
					 echo "Error al ejecutar procedimiento.\n";
					 die( print_r( odbc_error(), true));
				}else {
					$row = odbc_fetch_array($stmt);
					$id_seccion = $row['ID_SECCION'];
				}
				
				odbc_free_result( $stmt);
				
				/*
				*Inserta la sala de clases correspondiente
				*/
				$dec = "INSERT INTO SALACLASES OUTPUT Inserted.ID_SALA VALUES("
						. "'".$sala."',"
						. "'".$capacidad."')";
						
				$stmt = odbc_exec($this->conn,$dec);
				if( $stmt === false )
				{
					 echo "Error al ejecutar procedimiento.\n";
					 die( print_r( odbc_error(), true));
				}else{
					$row = odbc_fetch_array($stmt);
					$id_sala = $row['ID_SALA'];
				}
				
				odbc_free_result( $stmt);
				
				/*
				*Inserta la salaseccion correspondiente
				*/
				$dec = "INSERT INTO SALASECCION VALUES("
						. "'$id_seccion',"
						. "'".$id_sala."')";
						
				$stmt = odbc_exec($this->conn,$dec);
				if( $stmt === false )
				{
					 echo "Error al ejecutar procedimiento.\n";
					 die( print_r( odbc_error(), true));
				}
				
				odbc_free_result( $stmt);
				
				/*
				*Inserta la seccion asignatura correspondiente
				*/
				$dec = "INSERT INTO SECCIONASIG VALUES("
						. "'".$id_seccion."',"
						. "'".$id_asignatura."')";
						
				
				$stmt = odbc_exec($this->conn,$dec);
				if( $stmt === false )
				{
					 echo "Error al ejecutar procedimiento.\n";
					 die( print_r( odbc_error(), true));
				}
				
				odbc_free_result( $stmt);
				
				return $id_seccion;
			}	
		}
	}
	
	function FAM_INSERT_ALUMNO($nom, $ap1, $ap2, $rut, $pln){
		
		//Busca si existe un alumno con ese rut
		$dec = "SELECT * FROM ALUMNO WHERE RUT = '".$rut."'";
		$stmt = odbc_exec($this->conn,$dec);
		if( $stmt === false )
		{
			echo "Error al ejecutar procedimiento.\n";
			die( print_r( odbc_error(), true));
		}else{
			if(odbc_num_rows($stmt) > 0){
				return false;
			}else{
				$no_existe = true;
			}
		}
		
		odbc_free_result( $stmt);
		
		//Si no existe lo inserta
		if($no_existe){
			
			$nom_completo = $ap1." ".$ap2.", ".$nom;
			
			$dec = "INSERT INTO ALUMNO (NOMBRES, RUT, CODIGO_PLAN, ESTADO_ESTUDIO) VALUES("
				. "'".$nom_completo."',"
				. "'".$rut."',"
				. "'".$pln."',"
				. "'REGULAR')";				
				
			$stmt = odbc_exec($this->conn,$dec);
			if( $stmt === false )
			{
				echo "Error al ejecutar procedimiento.\n";
				die( print_r( odbc_error(), true));
			}
			odbc_free_result( $stmt);
			return true;
		}
	}
	
	function FAM_INSERT_NOTA($rut_alum, $cod_ramo, $_seccion, $ano_ramo, $sem_ramo, $estado_A){
		
		$dec = "INSERT INTO NOTA VALUES("
				. "'".$nom_completo."',"
				. "'".$rut."',"
				. "'".$pln."',"
				. "'REGULAR')";				
				
		$stmt = odbc_exec($this->conn,$dec);
		if( $stmt === false )
		{
			echo "Error al ejecutar procedimiento.\n";
			die( print_r( odbc_error(), true));
		}
		odbc_free_result( $stmt);
	}
}

?>

