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
		$this->DB_NAME = "FAM";
	//PRD
        //$this->DB_USER = "sa";
        //$this->DB_PASS = "informatica.2015*";
        //$this->DB_SERVER = "172.16.22.8";

	//QAS
        $this->DB_USER = "valentys_sql";
        $this->DB_PASS = "valentys.2012*";
        $this->DB_SERVER = "172.16.39.64";
		
        $this->conection();
    }
    
    function conection(){
    
		try{
			$this->conn = new PDO (
					"odbc:Driver={SQL Server Native Client 10.0};Server=".$this->DB_SERVER.";Port:1433;dbname=".$this->DB_NAME,
					$this->DB_USER,
					$this->DB_PASS
					);
					
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $ex){
			echo 'Connection failed: ' . $ex->getCode();
		}
    }
    
    function select_alumnos(){
        
		try{
			$result = array();
			$sql = "SELECT * FROM FAM.dbo.ALUMNO";
		
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
			
			$result = $stmt->fetchAll();
		}catch(PDOException $ex){
			echo 'Error en sentencia: ' . $ex->getMessage();
		} 
		
        return $result;
    }
    
    function select_asignaturas(){
        
		try{
			$asignaturas = array();
			$sql = 'SELECT * FROM FAM.dbo.ASIGNATURA ORDER BY NOM_ASIGNATURA';
			
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
			
			$result = $stmt->fetchAll();
			
			foreach($result as $row){
				$asignaturas[]['COD_ASIGNATURA'] = $row['COD_ASIGNATURA'];
				$asignaturas[count($asignaturas)-1]['NOM_ASIGNATURA'] = utf8_encode($row['NOM_ASIGNATURA']);
				$asignaturas[count($asignaturas)-1]['PLANESTUDIO_COD_PLANESTUDIO'] = $row['PLANESTUDIO_COD_PLANESTUDIO'];
			}
		}catch(PDOException $ex){
			echo 'Error en sentencia: ' . $ex->getMessage();
		}
        
        return $asignaturas;
    }
    
    function select_alumno_rut($rut){
		
		try{
			$alumno = array();
			$sql = "{CALL FAM.dbo.select_alumno_rut(?)}";
			
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(1, $rut, PDO::PARAM_STR);
			$stmt->execute();
			
			$result = $stmt->fetchAll();
			
			foreach($result as $row){
				$alumno['RUT'] = $row['RUT'];
				$alumno['N_MATRICULA'] = $row['N_MATRICULA'];
				$alumno['NOMBRES'] = utf8_encode($row['NOMBRES']);
				$alumno['CODIGO_PLAN'] = $row['CODIGO_PLAN'];
				$alumno['ESTADO_ESTUDIO'] = $row['ESTADO_ESTUDIO'];
			}
			
		}catch(PDOException $ex){
			echo 'Error en sentencia: ' . $ex->getCode();
		}
        
        return $alumno;
    }
	
	function SELECT_ASIGNATURAS_BYPLAN($codigo) {
		
		try{
			
			$salida = array();
			$sql = "SELECT * FROM FAM.dbo.ASIGNATURA WHERE PLANESTUDIO_COD_PLANESTUDIO = :PLANESTUDIO_COD_PLANESTUDIO";
			
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(':PLANESTUDIO_COD_PLANESTUDIO', $codigo, PDO::PARAM_STR);
			$stmt->execute();
			
			$asignaturas = $stmt->fetchAll();
			
			$sql = "SELECT DURACION FROM FAM.dbo.PLANESTUDIO WHERE COD_PLANESTUDIO = :COD_PLANESTUDIO";
			
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(':COD_PLANESTUDIO', $codigo, PDO::PARAM_STR);
			$stmt->execute();
			
			$result = $stmt->fetchAll();
			$duracion = $result[0]['DURACION'];
			
			$con = 1;
			while($con <= $duracion){
				$salida[$con] = array();
				$con++;
			}
			
			foreach($asignaturas as $row){
				$salida[$row['NIVEL']][] = array(
					$row['NOM_ASIGNATURA'],
					$row['COD_ASIGNATURA']
				);
			}
			
			$salida['DURACION'] = $duracion;
			
		}catch(PDOException $ex){
			echo 'Error en sentencia: ' . $ex->getCode();
		}
		
        return $salida;
	}
	
	function SELECT_SECCION_NOTA_BYRUT($rut){
		
		try{
			
			$secciones = array();
			$sql = "{CALL FAM.dbo.FAM_SELECT_SECCIONES_BYRUT(?)}";
			
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(1, $rut, PDO::PARAM_STR);
			$stmt->execute();
			
			$result = $stmt->fetchAll();
			
			foreach($result as $row){
				if((float) $row['NOTA'] > 4.0){
					$row['ESTADO'] = 'APROBADO';
				}else{
					$row['ESTADO'] = 'REPROBADO';
				}
				$secciones[] = $row;
			}
			
		}catch(PDOException $ex){
			echo 'Error en sentencia: ' . $ex->getCode();
		}
		
		return $secciones;
	}
	
	function FAM_VINCULAR_ALUM_SECC($codigo, $seccio, $año, $sem, $rut){
		
		try{
			$codigo_completo = $codigo."-".$seccio;
			$return = 1;
			$sql = '{CALL FAM.dbo.FAM_VINCULAR_ALUM_SECC(?,?,?,?,?)}';
			
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(1, $rut, PDO::PARAM_STR);
			$stmt->bindParam(2, $codigo_completo, PDO::PARAM_STR);
			$stmt->bindParam(3, $sem, PDO::PARAM_INT);
			$stmt->bindParam(4, $año, PDO::PARAM_INT);
			$stmt->bindParam(5, $return, PDO::PARAM_INT,4);
			$stmt->execute();
			
			return $return;
			
		}catch(PDOException $ex){
			echo 'Error en sentencia: ' . $ex->getMessage()."<br>";
		}
	}
    
    function FAM_PLAN_INSERT($codigo, $tipo, $facultad, $escuela,
            $sede, $grd_bach, $grd_acad, $titulo, $nombre, $jornada,$duracion){
        
		try{
			
			$escuela = 'ICC-01';
			$sql = "INSERT INTO FAM.dbo.CARRERA(NOM_CARRERA, JORNADA_CARRERA, ESCUELAUMAYOR_COD_ESCUELA) OUTPUT INSERTED.COD_CARRERA
			VALUES(:NOM_CARRERA,:JORNADA_CARRERA,:ESCUELAUMAYOR_COD_ESCUELA)";
			
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(':NOM_CARRERA', $nombre, PDO::PARAM_STR);
			$stmt->bindParam(':JORNADA_CARRERA', $jornada, PDO::PARAM_STR);
			$stmt->bindParam(':ESCUELAUMAYOR_COD_ESCUELA', $escuela, PDO::PARAM_STR);
			$stmt->execute();
			
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$id_carrera = $result['COD_CARRERA'];
			
			$sql = "INSERT INTO FAM.dbo.PLANESTUDIO(COD_PLANESTUDIO,CARRERA_COD_CARRERA,NOM_PLANESTUDIO,TIPO_PLAN,GRD_BACH,GRD_ACAD,TITULO,DURACION) 
			VALUES(:COD_PLANESTUDIO,:CARRERA_COD_CARRERA,:NOM_PLANESTUDIO,:TIPO_PLAN,:GRD_BACH,:GRD_ACAD,:TITULO,:DURACION)";
						
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(':COD_PLANESTUDIO', $codigo, PDO::PARAM_STR);
			$stmt->bindParam(':CARRERA_COD_CARRERA', $id_carrera, PDO::PARAM_INT);
			$stmt->bindParam(':NOM_PLANESTUDIO', $nombre, PDO::PARAM_STR);
			$stmt->bindParam(':TIPO_PLAN', $tipo, PDO::PARAM_STR);
			$stmt->bindParam(':GRD_BACH', $grd_bach, PDO::PARAM_STR);
			$stmt->bindParam(':GRD_ACAD', $grd_acad, PDO::PARAM_STR);
			$stmt->bindParam(':TITULO', $titulo, PDO::PARAM_STR);
			$stmt->bindParam(':DURACION', $duracion, PDO::PARAM_INT);
			$stmt->execute();
			
		}catch(PDOException $ex){
			'Error en sentencia: ' . $ex->getCode();
		}
    }  
	
	function FAM_ASIGNATURA_INSERT($codigo_plan, $nombre, $nivel, $codigo){
        
		try{
			$sql = "INSERT INTO FAM.dbo.ASIGNATURA(COD_ASIGNATURA,NOM_ASIGNATURA,PLANESTUDIO_COD_PLANESTUDIO,NIVEL)
			VALUES(:COD_ASIGNATURA,:NOM_ASIGNATURA,:PLANESTUDIO_COD_PLANESTUDIO,:NIVEL)";
			
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(':COD_ASIGNATURA', $codigo, PDO::PARAM_STR);
			$stmt->bindParam(':NOM_ASIGNATURA', $nombre, PDO::PARAM_STR);
			$stmt->bindParam(':PLANESTUDIO_COD_PLANESTUDIO', $codigo_plan, PDO::PARAM_STR);
			$stmt->bindParam(':NIVEL', $nivel, PDO::PARAM_INT);
			$stmt->execute();
			
		}catch(PDOException $ex){
			'Error en sentencia: ' . $ex->getCode();
		}
    }  
	
	function VERIFICAR_PLAN_EXISTENTE($codigo){
		
		try{
			
			$sql = "SELECT * FROM FAM.dbo.PLANESTUDIO WHERE COD_PLANESTUDIO = :COD_PLANESTUDIO";

			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(':COD_PLANESTUDIO', $codigo, PDO::PARAM_STR);
			$stmt->execute();

			$result = $stmt->fetchAll();
			
			if(count($result) > 0){
				return true;
			}else{
				return false;
			}
			
		}catch(PDOException $ex){
			'Error en sentencia: ' . $ex->getCode();
		}
	}
	
	function VERIFICAR_OFERTA_EXISTENTE($año, $semestre){
		
		try{
			$sql = "SELECT * FROM FAM.dbo.OFERTA WHERE ANO = :ANO AND SEMESTRE = :SEMESTRE";

			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(':ANO', $año, PDO::PARAM_INT);
			$stmt->bindParam(':SEMESTRE', $semestre, PDO::PARAM_INT);
			$stmt->execute();

			$result = $stmt->fetchAll();
			
			if(count($result) > 0){
				return true;
			}else{
				return false;
			}
			
		}catch(PDOException $ex){
			'Error en sentencia: ' . $ex->getCode();
		}
	}
	
	function VERIFICAR_SECCION_EXISTENTES($año, $semestre, $cod_ramo, $_seccion){
		
		//Selecciona a la oferta que corresponde la seccion que se vinculará
		$dec = "SELECT id_oferta FROM OFERTA WHERE ANO = '".$año."' AND SEMESTRE = '".$semestre."'";
		$stmt = odbc_exec($this->conn,$dec);
		if( $stmt === false ){
			echo "Error al ejecutar procedimiento.\n";
			die( print_r( odbc_error(), true));
		}else{
			$row = odbc_fetch_array($stmt);
			$id_oferta = $row['id_oferta'];
		}
		odbc_free_result( $stmt);
		
		$ramo = $cod_ramo.'-'.$_seccion;
		//Selecciona la ID de la seccion que se vinculara con la nota
		$dec = "SELECT * FROM SECCION WHERE COD_SECCION = '".$ramo."' AND OFERTA_ID = '".$id_oferta."'";
		
		$stmt = odbc_exec($this->conn,$dec);
		if( $stmt === false ){
			echo "Error al ejecutar procedimiento.\n";
			die( print_r( odbc_error(), true));
		}else{
			$row = odbc_fetch_array($stmt);
			$id_seccion = $row['ID_SECCION'];
		}
		odbc_free_result( $stmt);
		
		return $id_seccion;
	}
	
	function VERIFICAR_NOTAS_EXISTENTES($rut, $año, $semestre, $cod_ramo, $_seccion){
		
		$id_seccion = $this->VERIFICAR_SECCION_EXISTENTES($año, $semestre, $cod_ramo, $_seccion);
		
		$dec = "SELECT * FROM NOTA WHERE SEMESTRE = '".$semestre."' AND ANO = '".$año."' AND ID_SECCION = '".$id_seccion."' AND RUT_ALUMNO = '".$rut."'";
		
		$stmt = odbc_exec($this->conn,$dec);
		if( $stmt === false ){
			echo "Error al ejecutar procedimiento.\n";
			die( print_r( odbc_error(), true));
		}
		
		if(odbc_num_rows($stmt) > 0){
			odbc_free_result( $stmt);
			return true;
		}else{
			odbc_free_result( $stmt);
			return false;
		}
	}
	
	function FAM_OFERTA_INSERT($año, $semestre){
		
		try{
			$sql = "INSERT INTO FAM.dbo.OFERTA(ANO,SEMESTRE) OUTPUT Inserted.ID_OFERTA 
			VALUES(:ANO,:SEMESTRE)";
			
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(':ANO', $año, PDO::PARAM_INT);
			$stmt->bindParam(':SEMESTRE', $semestre, PDO::PARAM_INT);
			$stmt->execute();
			
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$id_oferta = $result['ID_OFERTA'];
			
		}catch(PDOException $ex){
			'Error en sentencia: ' . $ex->getCode();
		}
		return $id_oferta;
	}
	
	function FAM_SECCION_INSERT($codigo, $seccion, $profesor, $oferta_id, $inscritos, $cupos, $capacidad, $sala,
			$dia, $inicio, $termino, $modalidad){
		
			$id_seccion = 0;
		try{
			/*
			*SELECCIONA ID DE ASIGNATURA SEGUN CODIGO la seccion correspondiente
			*/
			$sql = "SELECT ID_ASIGNATURA FROM FAM.dbo.ASIGNATURA WHERE COD_ASIGNATURA = :COD_ASIGNATURA";
			
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(':COD_ASIGNATURA', $codigo, PDO::PARAM_STR);
			$stmt->execute();
			
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			if(count($result) == 0){
				
			}else{
				$id_asignatura = $result['ID_ASIGNATURA'];
				/*
				*Inserta la seccion correspondiente
				*/
				$sql = "INSERT INTO FAM.dbo.SECCION(COD_SECCION,PROFESOR_NOMBRE,OFERTA_ID,INSCRITOS,CUPOS,CAPACIDAD,DIA,INICIO,TERMINO,MODALIDAD) OUTPUT Inserted.ID_SECCION
				VALUES(:COD_SECCION,:PROFESOR_NOMBRE,:OFERTA_ID,:INSCRITOS,:CUPOS,:CAPACIDAD,:DIA,:INICIO,:TERMINO,:MODALIDAD)";
				
				$codigo_sec = $codigo."-".$seccion;
				
				$stmt = $this->conn->prepare($sql);
				$stmt->bindParam(':COD_SECCION', $codigo_sec, PDO::PARAM_STR);
				$stmt->bindParam(':PROFESOR_NOMBRE', $profesor, PDO::PARAM_STR);
				$stmt->bindParam(':OFERTA_ID', $oferta_id, PDO::PARAM_INT);
				$stmt->bindParam(':INSCRITOS', $inscritos, PDO::PARAM_INT);
				$stmt->bindParam(':CUPOS', $cupos, PDO::PARAM_INT);
				$stmt->bindParam(':CAPACIDAD', $capacidad, PDO::PARAM_INT);
				$stmt->bindParam(':DIA', $dia, PDO::PARAM_STR);
				$stmt->bindParam(':INICIO', $inicio, PDO::PARAM_STR);
				$stmt->bindParam(':TERMINO', $termino, PDO::PARAM_STR);
				$stmt->bindParam(':MODALIDAD', $modalidad, PDO::PARAM_STR);
				$stmt->execute();

				$result = $stmt->fetch(PDO::FETCH_ASSOC);
				$id_seccion = $result['ID_SECCION'];
				
						
				/*
				*Inserta la sala de clases correspondiente
				*/
				$sql = "INSERT INTO FAM.dbo.SALACLASES(COD_SALA,CANTIDAD_ALUMNOS) OUTPUT Inserted.ID_SALA
				VALUES(:COD_SALA,:CANTIDAD_ALUMNOS)";
				
				$stmt = $this->conn->prepare($sql);
				$stmt->bindParam(':COD_SALA', $sala, PDO::PARAM_STR);
				$stmt->bindParam(':CANTIDAD_ALUMNOS', $capacidad, PDO::PARAM_INT);
				$stmt->execute();
				
				$result = $stmt->fetch(PDO::FETCH_ASSOC);
				$id_sala = $result['ID_SALA'];
				
				/*
				*Inserta la salaseccion correspondiente
				*/
				$sql = "INSERT INTO FAM.dbo.SALASECCION(SECCION_COD_SECCION,ID_SALA)
				VALUES(:SECCION_COD_SECCION,:ID_SALA)";
				
				$stmt = $this->conn->prepare($sql);
				$stmt->bindParam(':SECCION_COD_SECCION', $id_seccion, PDO::PARAM_INT);
				$stmt->bindParam(':ID_SALA', $id_sala, PDO::PARAM_INT);
				$stmt->execute();
				
				/*
				*Inserta la seccion asignatura correspondiente
				*/
				$sql = "INSERT INTO FAM.dbo.SECCIONASIG(ID_SECCION,ID_ASIGNATURA)
				VALUES(:ID_SECCION,:ID_ASIGNATURA)";
				
				$stmt = $this->conn->prepare($sql);
				$stmt->bindParam(':ID_SECCION', $id_seccion, PDO::PARAM_INT);
				$stmt->bindParam(':ID_ASIGNATURA', $id_asignatura, PDO::PARAM_INT);
				$stmt->execute();
			}
			
		}catch(PDOException $ex){
			'Error en sentencia: ' . $ex->getCode();
		}
		
		return $id_seccion;		
	}
	
	function FAM_INSERT_ALUMNO($nom, $ap1, $ap2, $rut, $pln){
		
		try{
			//Busca si existe un alumno con ese rut
			$sql = "SELECT * FROM FAM.dbo.ALUMNO WHERE RUT = :RUT";
			
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(':RUT', $rut, PDO::PARAM_STR);
			$stmt->execute();
			
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			
			if(count($result) > 0){
				return false;
			}else{
				$no_existe = true;
			}
			
			//Si no existe lo inserta
			if($no_existe){
			
				$nom_completo = $ap1." ".$ap2.", ".$nom;
				$estado = "REGULAR";
				
				$dec = "INSERT INTO FAM.dbo.ALUMNO (NOMBRES, RUT, CODIGO_PLAN, ESTADO_ESTUDIO) 
				VALUES(:NOMBRES, :RUT, :CODIGO_PLAN, :ESTADO_ESTUDIO)";			
				
				$stmt = $this->conn->prepare($sql);
				$stmt->bindParam(':NOMBRES', $nom_completo, PDO::PARAM_STR);
				$stmt->bindParam(':RUT', $rut, PDO::PARAM_STR);
				$stmt->bindParam(':CODIGO_PLAN', $pln, PDO::PARAM_STR);
				$stmt->bindParam(':ESTADO_ESTUDIO', $estado, PDO::PARAM_STR);
				$stmt->execute();
				
				return true;
			}
			
		}catch(PDOException $ex){
			
		}
	}
	
	function FAM_INSERT_NOTA($rut_alum, $cod_ramo, $_seccion, $ano_ramo, $sem_ramo, $nota, $pond, $porc, $tipo){
		
		$id_seccion = $this->VERIFICAR_SECCION_EXISTENTES($ano_ramo, $sem_ramo, $cod_ramo, $_seccion);
		
		if($id_seccion === null){
			return false;
		}
		
		$porc = trim($porc,"%");
		//Inserta la nota correspondiente
		$dec = "INSERT INTO NOTA VALUES("
				. "'".$rut_alum."',"
				. "'".$ano_ramo."',"
				. "'".$sem_ramo."',"
				. "'".$id_seccion."',"
				. "'".$nota."',"
				. "'".$porc."',"
				. "'".$pond."',"
				. "'".$tipo."')";					
				
		$stmt = odbc_exec($this->conn,$dec);
		if( $stmt === false )
		{
			echo "Error al ejecutar procedimiento.\n";
			die( print_r( odbc_error(), true));
		}
		odbc_free_result( $stmt);
		return $id_seccion;
	}
	
	function FAM_INSERT_ALUM_SECC($rut_alum, $id_seccion){
		
		//INGRESA EL VINCULO
		$dec = "INSERT INTO ALUMNOSECCION VALUES("
				. "'".$id_seccion."',"
				. "'".$rut_alum."')";
		
		$stmt = odbc_exec($this->conn,$dec);
		
		if( $stmt === false ){
			echo "Error al ejecutar procedimiento.\n";
			die( print_r( odbc_error(), true));
		}
		
		odbc_free_result( $stmt);
	}
}

?>

