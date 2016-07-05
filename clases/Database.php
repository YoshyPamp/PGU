<?php
include(dirname(__DIR__)."\Config\Configuracion.php");

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
    
    public $DB_NAME;
    private $DB_USER;
    private $DB_PASS;
    private $DB_SERVER;
    private $connectionInfo;
    public $conn;
    
        function __construct() {
            
            $this->DB_NAME = Configuracion::$DB_NAME;
            $this->DB_USER = Configuracion::$DB_USER;
            $this->DB_PASS = Configuracion::$DB_PASS;
            $this->DB_SERVER = Configuracion::$DB_SERVER;

            $this->conection();
        }
    
        function conection(){
    
            try{
                    $this->conn = new PDO (
                                    "odbc:Driver={SQL Server Native Client 11.0};Server=".$this->DB_SERVER.";Port:1433;dbname=".$this->DB_NAME,
                                    $this->DB_USER,
                                    $this->DB_PASS
                                    );

                    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }catch(PDOException $ex){
                    echo 'Connection failed: ' . $ex->getMessage();
            }
        }
    
        function FAM_SELECT_ALUMNOS(){
        
            try{
                    $sql = "SELECT * FROM $this->DB_NAME.dbo.ALUMNO";

                    $stmt = $this->conn->prepare($sql);
                    $stmt->execute();

                    $result = $stmt->fetchAll();

            }catch(PDOException $ex){
                    echo 'Error en sentencia: ' . $ex->getMessage();
            } 
		
            return $result;
        }
        
        function FAM_SELECT_USUARIOS(){
            try{
                    $sql = "SELECT "
                            . "ID_USUARIO, USUARIO, EMAIL, RUT_ALUMNO, ID_PERFIL, BLOQUEADO "
                            . "FROM $this->DB_NAME.dbo.SGU_USUARIO ORDER BY USUARIO";

                    $stmt = $this->conn->prepare($sql);
                    $stmt->execute();

                    $result = $stmt->fetchAll();
                    
                    if(count($result) == 0){
                        return '';
                    }

            }catch(PDOException $ex){
                    echo 'Error en sentencia select: ' . $ex->getMessage();
            } 
		
            return $result;
        }
        
        function FAM_UPDATE_USUARIOS($id_usuario, $usuario, $mail, $rut_alumno, $perfil){
            
            try{
                $alumno = array();
                $sql = "{CALL $this->DB_NAME.dbo.FAM_UPDATE_USUARIOS(?,?,?,?,?)}";

                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(1, $id_usuario, PDO::PARAM_INT);
                $stmt->bindParam(2, $usuario, PDO::PARAM_STR);
                $stmt->bindParam(3, $mail, PDO::PARAM_STR);
                $stmt->bindParam(4, $rut_alumno, PDO::PARAM_STR);
                $stmt->bindParam(5, $perfil, PDO::PARAM_INT);
                $stmt->execute();
			
            }catch(PDOException $ex){
                echo 'Error en sentencia update: ' . $ex->getMessage();
            }
        }
    
        function FAM_SELECT_ASIGNATURAS(){
        
            try{
                    $asignaturas = array();
                    $sql = "SELECT * FROM $this->DB_NAME.dbo.ASIGNATURA ORDER BY NOM_ASIGNATURA";

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
        
        function FAM_SELECT_ASIGNATURAS_ALL_HOMOLOGACION(){
        
            try{
                $asignaturas = array();
                $sql = "{CALL $this->DB_NAME.dbo.FAM_SELECT_ASIGNATURAS_ALL_HOMOLOGACION()}";

                $stmt = $this->conn->prepare($sql);
                $stmt->execute();

                $result = $stmt->fetchAll();

                foreach($result as $row){
                    $asignaturas[]['COD_ASIGNATURA'] = $row['COD_ASIGNATURA'];
                    $asignaturas[count($asignaturas)-1]['NOM_ASIGNATURA'] = utf8_encode($row['NOM_ASIGNATURA']);
                }
			
            }catch(PDOException $ex){
                echo 'Error en sentencia: ' . $ex->getCode();
            }
        
            return $asignaturas;
        }
    
        function FAM_SELECT_ALUMNO_RUT($rut){
		
		try{
			$alumno = array();
			$sql = "{CALL $this->DB_NAME.dbo.select_alumno_rut(?)}";
			
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(1, $rut, PDO::PARAM_STR);
			$stmt->execute();
			
			$result = $stmt->fetchAll();
                        
                        if(count($result) > 0){
			
                            foreach($result as $row){
                                    $alumno['RUT'] = $row['RUT'];
                                    $alumno['N_MATRICULA'] = $row['N_MATRICULA'];
                                    $alumno['NOMBRES'] = utf8_encode($row['NOMBRES']);
                                    $alumno['CODIGO_PLAN'] = $row['CODIGO_PLAN'];
                                    $alumno['ESTADO_ESTUDIO'] = $row['ESTADO_ESTUDIO'];
                                    $alumno['ESTADO_PRACTICA'] = $row['ESTADO_PRACTICA'];
                                    $alumno['COMENTARIO_PRACTICA'] = $row['COMENTARIO_PRACTICA'];
                                    $alumno['ESTADO_PRACTICA_PRO'] = $row['ESTADO_PRACTICA_PRO'];
                                    $alumno['COMENTARIO_PRACTICA_PRO'] = $row['COMENTARIO_PRACTICA_PRO'];
                            }
                        }
			
		}catch(PDOException $ex){
			echo 'Error en sentencia: ' . $ex->getCode();
		}
        
        return $alumno;
    }
	
        function SELECT_ASIGNATURAS_BYPLAN($codigo) {
		
		try{
			
			$salida = array();
			$sql = "SELECT * FROM $this->DB_NAME.dbo.ASIGNATURA WHERE PLANESTUDIO_COD_PLANESTUDIO = :PLANESTUDIO_COD_PLANESTUDIO";
			
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(':PLANESTUDIO_COD_PLANESTUDIO', $codigo, PDO::PARAM_STR);
			$stmt->execute();
			
			$asignaturas = $stmt->fetchAll();
			
			$sql = "SELECT DURACION FROM $this->DB_NAME.dbo.PLANESTUDIO WHERE COD_PLANESTUDIO = :COD_PLANESTUDIO";
			
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
                    $sql = "{CALL $this->DB_NAME.dbo.FAM_SELECT_SECCIONES_BYRUT(?)}";

                    $stmt = $this->conn->prepare($sql);
                    $stmt->bindParam(1, $rut, PDO::PARAM_STR);
                    $stmt->execute();

                    $result = $stmt->fetchAll();

                    foreach($result as $row){
                            if((float) $row['NOTA'] >= 4.0){
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
        
        function FAM_SELECT_SECCIONES_ALUMNO_ALL($rut){
            
            try{
                    $secciones = array();
                    $sql = "{CALL $this->DB_NAME.dbo.FAM_SELECT_SECCIONES_ALUMNO_ALL(?)}";

                    $stmt = $this->conn->prepare($sql);
                    $stmt->bindParam(1, $rut, PDO::PARAM_STR);
                    $stmt->execute();

                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    return $result;
                    
		}catch(PDOException $ex){
			echo 'Error en sentencia select: ' . $ex->getCode();
		}
            
        }
        
        function FAM_SELECT_ALUMNOS_SECCION($cod_sec, $ano, $sem){
            try{
                
                    $sql = "SELECT ID_OFERTA FROM $this->DB_NAME.dbo.OFERTA WHERE ANO = :ANO AND SEMESTRE = :SEMESTRE";
			
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bindParam(':ANO', $ano, PDO::PARAM_INT);
                    $stmt->bindParam(':SEMESTRE', $sem, PDO::PARAM_INT);
                    $stmt->execute();
                
                    $result1 = $stmt->fetchAll();
		    $id_oferta = $result1[0]['ID_OFERTA'];

                    $sql = "SELECT ID_SECCION FROM $this->DB_NAME.dbo.SECCION WHERE COD_SECCION = :COD_SECCION AND OFERTA_ID = :OFERTA_ID";
			
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bindParam(':COD_SECCION', $cod_sec, PDO::PARAM_STR);
                    $stmt->bindParam(':OFERTA_ID', $id_oferta, PDO::PARAM_INT);
                    $stmt->execute();
                
                    $result2 = $stmt->fetchAll();
		    $id_seccion = $result2[0]['ID_SECCION'];
                    
                    $sql = "{CALL $this->DB_NAME.dbo.FAM_SELECT_ALUMNOS_SECCION(?)}";

                    $stmt = $this->conn->prepare($sql);
                    $stmt->bindParam(1, $id_seccion, PDO::PARAM_INT);
                    $stmt->execute();

                    $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    return $alumnos;
                    
		}catch(PDOException $ex){
			echo 'Error en sentencia select: ' . $ex->getCode();
		}
        }
	
        function FAM_VINCULAR_ALUM_SECC($codigo, $seccio, $año, $sem, $rut, $resultado_ramo){
		
		try{
			$codigo_completo = $codigo."-".$seccio;
			$return = 1;
			$sql = "{CALL $this->DB_NAME.dbo.FAM_VINCULAR_ALUM_SECC(?,?,?,?,?,?)}";
			
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(1, $rut, PDO::PARAM_STR);
			$stmt->bindParam(2, $codigo_completo, PDO::PARAM_STR);
			$stmt->bindParam(3, $sem, PDO::PARAM_INT);
			$stmt->bindParam(4, $año, PDO::PARAM_INT);
			$stmt->bindParam(5, $return, PDO::PARAM_INT,4);
                        $stmt->bindParam(6, $resultado_ramo, PDO::PARAM_STR);
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
			$sql = "INSERT INTO $this->DB_NAME.dbo.CARRERA(NOM_CARRERA, JORNADA_CARRERA, ESCUELAUMAYOR_COD_ESCUELA) OUTPUT INSERTED.COD_CARRERA
			VALUES(:NOM_CARRERA,:JORNADA_CARRERA,:ESCUELAUMAYOR_COD_ESCUELA)";
			
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(':NOM_CARRERA', $nombre, PDO::PARAM_STR);
			$stmt->bindParam(':JORNADA_CARRERA', $jornada, PDO::PARAM_STR);
			$stmt->bindParam(':ESCUELAUMAYOR_COD_ESCUELA', $escuela, PDO::PARAM_STR);
			$stmt->execute();
			
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$id_carrera = $result['COD_CARRERA'];
			
			$sql = "INSERT INTO $this->DB_NAME.dbo.PLANESTUDIO(COD_PLANESTUDIO,CARRERA_COD_CARRERA,NOM_PLANESTUDIO,TIPO_PLAN,GRD_BACH,GRD_ACAD,TITULO,DURACION) 
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
			$sql = "INSERT INTO $this->DB_NAME.dbo.ASIGNATURA(COD_ASIGNATURA,NOM_ASIGNATURA,PLANESTUDIO_COD_PLANESTUDIO,NIVEL)
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
			
			$sql = "SELECT * FROM $this->DB_NAME.dbo.PLANESTUDIO WHERE COD_PLANESTUDIO = :COD_PLANESTUDIO";

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
	
        function VERIFICAR_OFERTA_EXISTENTE($ano, $semestre, $escuela){
		
		try{
			$sql = "SELECT * FROM $this->DB_NAME.dbo.OFERTA WHERE ANO = :ANO AND SEMESTRE = :SEMESTRE AND ESCUELA = :ESCUELA";

			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(':ANO', $ano, PDO::PARAM_INT);
			$stmt->bindParam(':SEMESTRE', $semestre, PDO::PARAM_INT);
                        $stmt->bindParam(':ESCUELA', $escuela, PDO::PARAM_STR);
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

        function VERIFICAR_SECCION_EXISTENTES($ano, $semestre, $cod_ramo, $_seccion){
		
            $id_seccion = 1;
                try{
                    //Selecciona a la oferta que corresponde la seccion que se vinculará
                    $ramo = $cod_ramo.'-'.$_seccion;
                    
                    $sql = "SELECT id_oferta FROM $this->DB_NAME.dbo.OFERTA WHERE ANO = :ANO AND SEMESTRE = :SEMESTRE";
                    
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bindParam(":ANO", $ano, PDO::PARAM_INT);
                    $stmt->bindParam(":SEMESTRE", $semestre, PDO::PARAM_INT);
                    $stmt->execute();
                    
                    $result = $stmt->fetchAll();
                    if(count($result) > 0){
                        $id_oferta = $result[0]['id_oferta'];
                    }
		    
                    
                    //Selecciona la ID de la seccion que se vinculara con la nota
                    $sql = "SELECT id_seccion FROM $this->DB_NAME.dbo.SECCION WHERE COD_SECCION = :COD_SECCION AND OFERTA_ID = :OFERTA_ID";
                    
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bindParam(":COD_SECCION", $ramo, PDO::PARAM_STR);
                    $stmt->bindParam(":OFERTA_ID", $id_oferta, PDO::PARAM_INT);
                    $stmt->execute();
                    
                    $result = $stmt->fetchAll();
                    if(count($result) > 0){
                        $id_seccion = $result[0]['id_seccion'];
                    }else{
                        $id_seccion = 0;
                    } 
                } catch (PDOException $ex) {
                    echo 'Error en sentencia: ' . $ex->getMessage()."<br>";
                }
   
		return $id_seccion;
	}
	
        function VERIFICAR_NOTAS_EXISTENTES($rut, $ano, $semestre, $cod_ramo, $_seccion){
		
            try{
                $id_seccion = $this->VERIFICAR_SECCION_EXISTENTES($ano, $semestre, $cod_ramo, $_seccion);
                
                if($id_seccion == 0){
                    return -2;
                }else{
                    $sql = "SELECT * FROM $this->DB_NAME.dbo.NOTA WHERE SEMESTRE = :SEMESTRE AND ANO = :ANO AND ID_SECCION = :ID_SECCION AND RUT_ALUMNO = :RUT_ALUMNO";
                    
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bindParam(":SEMESTRE", $semestre, PDO::PARAM_INT);
                    $stmt->bindParam(":ANO", $ano, PDO::PARAM_INT);
                    $stmt->bindParam(":ID_SECCION", $id_seccion, PDO::PARAM_INT);
                    $stmt->bindParam(":RUT_ALUMNO", $rut, PDO::PARAM_STR);
                    $stmt->execute();
                    
                    $result = $stmt->fetchAll();
                    if(count($result) > 0){
                        return -1;
                    }else{
                        return 0;
                    } 
                }
            
            } catch (PDOException $ex) {
                echo 'Error en sentencia: ' . $ex->getMessage()."<br>";
            }
	}
	
        function FAM_OFERTA_INSERT($ano, $semestre, $escuela){
		
		try{
			$sql = "INSERT INTO $this->DB_NAME.dbo.OFERTA(ANO,SEMESTRE,ESCUELA) OUTPUT Inserted.ID_OFERTA 
			VALUES(:ANO,:SEMESTRE,:ESCUELA)";
			
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(':ANO', $ano, PDO::PARAM_INT);
			$stmt->bindParam(':SEMESTRE', $semestre, PDO::PARAM_INT);
                        $stmt->bindParam(':ESCUELA', $escuela, PDO::PARAM_STR);
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
			$sql = "SELECT ID_ASIGNATURA FROM $this->DB_NAME.dbo.ASIGNATURA WHERE COD_ASIGNATURA = :COD_ASIGNATURA";
			
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
				$sql = "INSERT INTO $this->DB_NAME.dbo.SECCION(COD_SECCION,PROFESOR_NOMBRE,OFERTA_ID,INSCRITOS,CUPOS,CAPACIDAD,DIA,INICIO,TERMINO,MODALIDAD) OUTPUT Inserted.ID_SECCION
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
				$sql = "INSERT INTO $this->DB_NAME.dbo.SALACLASES(COD_SALA,CANTIDAD_ALUMNOS,ID_OFERTA) OUTPUT Inserted.ID_SALA
				VALUES(:COD_SALA,:CANTIDAD_ALUMNOS,:ID_OFERTA)";
				
				$stmt = $this->conn->prepare($sql);
				$stmt->bindParam(':COD_SALA', $sala, PDO::PARAM_STR);
				$stmt->bindParam(':CANTIDAD_ALUMNOS', $capacidad, PDO::PARAM_INT);
                                $stmt->bindParam(':ID_OFERTA', $oferta_id, PDO::PARAM_INT);
				$stmt->execute();
				
				$result = $stmt->fetch(PDO::FETCH_ASSOC);
				$id_sala = $result['ID_SALA'];
				
				/*
				*Inserta la salaseccion correspondiente
				*/
				$sql = "INSERT INTO $this->DB_NAME.dbo.SALASECCION(SECCION_COD_SECCION,ID_SALA,ID_OFERTA)
				VALUES(:SECCION_COD_SECCION,:ID_SALA,:ID_OFERTA)";
				
				$stmt = $this->conn->prepare($sql);
				$stmt->bindParam(':SECCION_COD_SECCION', $id_seccion, PDO::PARAM_INT);
				$stmt->bindParam(':ID_SALA', $id_sala, PDO::PARAM_INT);
                                $stmt->bindParam(':ID_OFERTA', $oferta_id, PDO::PARAM_INT);
				$stmt->execute();
				
				/*
				*Inserta la seccion asignatura correspondiente
				*/
				$sql = "INSERT INTO $this->DB_NAME.dbo.SECCIONASIG(ID_SECCION,ID_ASIGNATURA,ID_OFERTA)
				VALUES(:ID_SECCION,:ID_ASIGNATURA,:ID_OFERTA)";
				
				$stmt = $this->conn->prepare($sql);
				$stmt->bindParam(':ID_SECCION', $id_seccion, PDO::PARAM_INT);
				$stmt->bindParam(':ID_ASIGNATURA', $id_asignatura, PDO::PARAM_INT);
                                $stmt->bindParam(':ID_OFERTA', $oferta_id, PDO::PARAM_INT);
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
			$sql = "SELECT * FROM $this->DB_NAME.dbo.ALUMNO WHERE RUT = :RUT";
			
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(':RUT', $rut, PDO::PARAM_STR);
			$stmt->execute();
			
			$result = $stmt->fetchAll();

			if(!empty($result)){
				return false;
			}else{
				$no_existe = true;
			}
			
			//Si no existe lo inserta
			if($no_existe){
			
				$nom_completo = utf8_encode($ap1." ".$ap2.", ".$nom);
				$estado = "REGULAR";

				$sql = "INSERT INTO $this->DB_NAME.dbo.ALUMNO (NOMBRES, RUT, CODIGO_PLAN, ESTADO_ESTUDIO) 
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
			echo 'Error en sentencia: ' . $ex->getCode();
		}
	}
	
        function FAM_INSERT_NOTA($rut_alum, $cod_ramo, $_seccion, $ano_ramo, $sem_ramo, $nota, $pond, $porc, $tipo){
		
            try{
                $id_seccion = $this->VERIFICAR_SECCION_EXISTENTES($ano_ramo, $sem_ramo, $cod_ramo, $_seccion);
                $porc = trim($porc,"%");
                //Inserta la nota correspondiente
		$sql = "INSERT INTO $this->DB_NAME.dbo.NOTA (RUT_ALUMNO,ANO,SEMESTRE,ID_SECCION,NOTA,PORCENTAJE,NOTA_PONDERADA,TIPO_NOTA) "
                        . "VALUES(:RUT_ALUMNO,:ANO,:SEMESTRE,:ID_SECCION,:NOTA,:PORCENTAJE,:NOTA_PONDERADA,:TIPO_NOTA)";
                
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(":RUT_ALUMNO",$rut_alum, PDO::PARAM_STR);
                $stmt->bindParam(":ANO",$ano_ramo, PDO::PARAM_INT);
                $stmt->bindParam(":SEMESTRE",$sem_ramo, PDO::PARAM_INT);
                $stmt->bindParam(":ID_SECCION",$id_seccion, PDO::PARAM_INT);
                $stmt->bindParam(":NOTA",$nota, PDO::PARAM_INT);
                $stmt->bindParam(":PORCENTAJE",$porc, PDO::PARAM_INT);
                $stmt->bindParam(":NOTA_PONDERADA",$pond, PDO::PARAM_INT);
                $stmt->bindParam(":TIPO_NOTA",$tipo, PDO::PARAM_STR);
                $stmt->execute();
                
                if($tipo == 'NF'){
                    if($nota >= 4){
                        $tipo = 'A';
                    }else{
                        $tipo = 'S/N';
                    }
                }
                
                switch($tipo){
                    case 'A':
                        $sql = "UPDATE $this->DB_NAME.dbo.ALUMNOSECCION SET ESTADO = 'APROBADO' WHERE ID_SECCION = :ID_SECCION AND ALUMNO_RUT = :ALUMNO_RUT";
                        
                        $stmt = $this->conn->prepare($sql);
                        $stmt->bindParam(":ID_SECCION", $id_seccion, PDO::PARAM_INT);
                        $stmt->bindParam(":ALUMNO_RUT", $rut_alum, PDO::PARAM_STR);
                        $stmt->execute();
                        break;
                    case 'S/N':
                        $sql = "UPDATE $this->DB_NAME.dbo.ALUMNOSECCION SET ESTADO = 'REPROBADO' WHERE ID_SECCION = :ID_SECCION AND ALUMNO_RUT = :ALUMNO_RUT";
                        
                        $stmt = $this->conn->prepare($sql);
                        $stmt->bindParam(":ID_SECCION", $id_seccion, PDO::PARAM_INT);
                        $stmt->bindParam(":ALUMNO_RUT", $rut_alum, PDO::PARAM_STR);
                        $stmt->execute();
                        break;
                    default:
                        break;
                }
                            	
                
            } catch (PDOException $ex) {
                echo 'Error en sentencia: ' . $ex->getCode();
            }
		
            return $id_seccion;
	}
        
        function FAM_SELECT_ESTADO_ASIGNATURA_BY_RUT($rut, $asig){
            try{
                $sql = "{CALL $this->DB_NAME.dbo.FAM_SELECT_ESTADO_ASIGNATURA_BY_RUT(?,?)}";
                
                $stmt = $this->conn->prepare($sql);
			$stmt->bindParam(1, $rut, PDO::PARAM_STR);
                        $stmt->bindParam(2, $asig, PDO::PARAM_STR);
			$stmt->execute();
			
			$result = $stmt->fetchAll();
                        if(count($result) > 0){
                            $estado = $result[0]['ESTADO'];
                        }else{
                            $estado = '';
                        }
                        
                        switch($estado){
                            case 'REPROBADO':
                                $estado = 'danger';
                                break;
                            case 'APROBADO':
                                $estado = 'success';
                                break;
                            case 'INSCRITO':
                                $estado = 'warning';
                                break;
                            default:
                                $estado = 'info';
                                break;
                        }
                        
                        return $estado;
                
            } catch (Exception $ex) {
                'Error en sentencia: ' . $ex->getCode();
            }
        }
        function FAM_SELECT_ESTADO_ASIGNATURA_BY_RUT_PLUS($rut,$asignatura){
            try{
                $sql = "{CALL $this->DB_NAME.dbo.FAM_SELECT_ESTADO_ASIGNATURA_BY_RUT_PLUS(?,?)}";
                
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(1, $rut, PDO::PARAM_STR);
                $stmt->bindParam(2, $asignatura, PDO::PARAM_STR);
                $stmt->execute();
			
                $result = $stmt->fetchAll();
                if(count($result) > 0){
                    $estado = $result[0]['ESTADO'];
                }else{
                    $estado = '';
                }
                        
                switch($estado){
                    case 'REPROBADO':
                        $estado = 'danger';
                        break;
                    case 'APROBADO':
                        $estado = 'success';
                        break;
                    case 'INSCRITO':
                        $estado = 'warning';
                        break;
                    default:
                        $estado = 'info';
                        break;
                }

                return $estado;
                
            } catch (Exception $ex) {
                'Error en sentencia: ' . $ex->getCode();
            }
        }
        
        function FAM_SELECT_NOTAS_SECCION_BY_RUT($seccion, $rut){
            
            try{
                $sql = "{CALL $this->DB_NAME.dbo.FAM_SELECT_NOTAS_SECCION_BY_RUT(?,?)}";
                
                $stmt = $this->conn->prepare($sql);
			$stmt->bindParam(1, $rut, PDO::PARAM_STR);
                        $stmt->bindParam(2, $seccion, PDO::PARAM_INT);
			$stmt->execute();
			
			$result = $stmt->fetchAll();
                        return $result;
                
            } catch (Exception $ex) {
                'Error en sentencia: ' . $ex->getCode();
            }
        }
        
        function FAM_SELECT_SECCIONES_BYRUT($rut){
            try{
                $sql = "{CALL $this->DB_NAME.dbo.FAM_SELECT_SECCIONES_BYRUT(?)}";
                
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(1, $rut, PDO::PARAM_STR);
                $stmt->execute();

                $result = $stmt->fetchAll();
                return $result;
                
            } catch (Exception $ex) {
                'Error en sentencia: ' . $ex->getCode();
            }
        }
        
        function FAM_SELECT_SECCIONES_BY_RUT_OFERTA($rut, $año, $sem){
          
            try{
                $sql = "{CALL $this->DB_NAME.dbo.FAM_SELECT_SECCIONES_BY_RUT_OFERTA(?,?,?)}";
                
                $stmt = $this->conn->prepare($sql);
			$stmt->bindParam(1, $rut, PDO::PARAM_STR);
                        $stmt->bindParam(2, $año, PDO::PARAM_INT);
                        $stmt->bindParam(3, $sem, PDO::PARAM_INT);
			$stmt->execute();
			
			$result = $stmt->fetchAll();
                        return $result;
                
            } catch (Exception $ex) {
                echo 'Error en sentencia: ' . $ex->getCode();
            }
        }
        
        function FAM_UPDATE_ALUMNO($rut, $matricula, $nombres, $estado, $plan){
            
            try{
                $sql = "{CALL $this->DB_NAME.dbo.FAM_UPDATE_ALUMNO(?,?,?,?,?)}";
                
                $stmt = $this->conn->prepare($sql);
			$stmt->bindParam(1, $rut, PDO::PARAM_STR);
                        $stmt->bindParam(2, $matricula, PDO::PARAM_INT);
                        $stmt->bindParam(3, $nombres, PDO::PARAM_STR);
                        $stmt->bindParam(4, $estado, PDO::PARAM_STR);
                        $stmt->bindParam(5, $plan, PDO::PARAM_STR);
			$stmt->execute();
                
            } catch (Exception $ex) {
                echo 'Error en sentencia update: ' . $ex->getCode();
            }
        }
        
        function FAM_SELECT_OFERTA($ano, $sem, $escuela){
            
            try{
                $sql = "SELECT * FROM $this->DB_NAME.dbo.OFERTA WHERE ANO = :ANO AND SEMESTRE = :SEMESTRE AND ESCUELA = :ESCUELA";
			
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':ANO', $ano, PDO::PARAM_INT);
                $stmt->bindParam(':SEMESTRE', $sem, PDO::PARAM_INT);
                $stmt->bindParam(':ESCUELA', $escuela, PDO::PARAM_STR);
                $stmt->execute();

                $oferta = $stmt->fetchAll();
                
                if(count($oferta) > 0){
                    $oferta_id = $oferta[0]['ID_OFERTA'];
                    
                    $sql = "SELECT * FROM $this->DB_NAME.dbo.SECCION WHERE OFERTA_ID = :OFERTA_ID";
			
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bindParam(':OFERTA_ID', $oferta_id, PDO::PARAM_INT);
                    $stmt->execute();

                    $secciones = $stmt->fetchAll();
                    return $secciones;
                }else{
                    return '';
                } 
            } catch (Exception $ex) {
                echo 'Error en sentencia select: ' . $ex->getCode();
            }
        }
        
        function FAM_SELECT_PLAN_ESTUDIO($codigo){
            try{
                $sql = "SELECT * FROM $this->DB_NAME.dbo.PLANESTUDIO WHERE COD_PLANESTUDIO = :COD_PLANESTUDIO";
			
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':COD_PLANESTUDIO', $codigo, PDO::PARAM_STR);
                $stmt->execute();

                $resultado = $stmt->fetchAll();
                if(count($resultado) > 0){
			
                    foreach($resultado as $row){
                        $plan_estudio['COD_PLANESTUDIO'] = $row['COD_PLANESTUDIO'];
                        $plan_estudio['NOM_PLANESTUDIO'] = utf8_encode($row['NOM_PLANESTUDIO']);
                        $plan_estudio['TIPO_PLAN'] = $row['TIPO_PLAN'];
                        $plan_estudio['GRD_BACH'] = utf8_encode($row['GRD_BACH']);
                        $plan_estudio['GRD_ACAD'] = utf8_encode($row['GRD_ACAD']);
                        $plan_estudio['TITULO'] = utf8_encode($row['TITULO']);
                        $plan_estudio['DURACION'] = $row['DURACION'];
                    }
                }
                
                return $plan_estudio;
                
            } catch (Exception $ex) {
                echo 'Error en sentencia select: ' . $ex->getCode();
            }
        }
        
        function FAM_SELECT_ASIGNATURAS_PLAN($plan,$tipo){
            try{
                if($tipo == "proyeccion"){
                    $sql = "SELECT * FROM $this->DB_NAME.dbo.ASIGNATURA WHERE PLANESTUDIO_COD_PLANESTUDIO = :PLANESTUDIO_COD_PLANESTUDIO "
                            . "ORDER BY NIVEL";
                }else{
                    $sql = "SELECT * FROM $this->DB_NAME.dbo.ASIGNATURA WHERE PLANESTUDIO_COD_PLANESTUDIO = :PLANESTUDIO_COD_PLANESTUDIO "
                            . "ORDER BY NOM_ASIGNATURA";
                }
			
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':PLANESTUDIO_COD_PLANESTUDIO', $plan, PDO::PARAM_STR);
                $stmt->execute();

                $asignaturas_plan = array();
                $resultado = $stmt->fetchAll();
                if(count($resultado) > 0){
			
                    foreach($resultado as $row){
                        $plan_asignatura['ID_ASIGNATURA'] = $row['ID_ASIGNATURA'];
                        $plan_asignatura['COD_ASIGNATURA'] = $row['COD_ASIGNATURA'];
                        $plan_asignatura['NOM_ASIGNATURA'] = utf8_encode($row['NOM_ASIGNATURA']);
                        $plan_asignatura['NIVEL'] = $row['NIVEL'];
                        $asignaturas_plan[] = $plan_asignatura;
                    }
                }
                
                return $asignaturas_plan;
                
            } catch (Exception $ex) {
                echo 'Error en sentencia select: ' . $ex->getCode();
            }
        }
        
        function FAM_SELECT_SECCIONES_CODIGO_ASIGNATURA($codigo_asignatura){
            try{
                $sql = "{CALL $this->DB_NAME.dbo.FAM_SELECT_SECCIONES_CODIGO_ASIGNATURA(?)}";
			
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(1, $codigo_asignatura, PDO::PARAM_STR);
                $stmt->execute();

                $secciones_asignatura = array();
                $resultado = $stmt->fetchAll();
                if(count($resultado) > 0){
			
                    foreach($resultado as $row){
                        $seccion['ID_SECCION'] = $row['ID_SECCION'];
                        $seccion['COD_SECCION'] = $row['COD_SECCION'];
                        $seccion['DIA'] = utf8_encode($row['DIA']);
                        $seccion['INICIO'] = $row['INICIO'];
                        $seccion['TERMINO'] = $row['TERMINO'];
                        $seccion['MODALIDAD'] = $row['MODALIDAD'];
                        $seccion['PROFESOR_NOMBRE'] = utf8_encode($row['PROFESOR_NOMBRE']);
                        $seccion['NOM_ASIGNATURA'] = utf8_encode($row['NOM_ASIGNATURA']);
                        $seccion['NIVEL'] = $row['NIVEL'];
                        $seccion['PLANESTUDIO_COD_PLANESTUDIO'] = $row['PLANESTUDIO_COD_PLANESTUDIO'];
                        $seccion['ANO'] = $row['ANO'];
                        $seccion['SEMESTRE'] = $row['SEMESTRE'];
                        $secciones_asignatura[] = $seccion;
                    }
                }
                
                return $secciones_asignatura;
                
            } catch (Exception $ex) {
                echo 'Error en sentencia select: ' . $ex->getCode();
            }
        }
        
        function FAM_UPDATE_DATOS_PLAN($codigo_plan, $nombre, $grd_bac, $grd_aca, $titulo, $tipo, $duracion){
            try{
                $sql = "{CALL $this->DB_NAME.dbo.FAM_UPDATE_DATOS_PLAN(?,?,?,?,?,?,?)}";
                
                $stmt = $this->conn->prepare($sql);
			$stmt->bindParam(1, $codigo_plan, PDO::PARAM_STR);
                        $stmt->bindParam(2, $nombre, PDO::PARAM_STR);
                        $stmt->bindParam(3, $grd_bac, PDO::PARAM_STR);
                        $stmt->bindParam(4, $grd_aca, PDO::PARAM_STR);
                        $stmt->bindParam(5, $titulo, PDO::PARAM_STR);
                        $stmt->bindParam(6, $tipo, PDO::PARAM_STR);
                        $stmt->bindParam(7, $duracion, PDO::PARAM_INT);
			$stmt->execute();
                
            }catch(Exception $ex){
                echo 'Error en sentencia update: ' . $ex->getCode();
            }
        }
        
        function FAM_UPDATE_ASIGNATURAS_PLAN($id_asignatura, $codigo, $nombre, $nivel){
            try{
                $sql = "{CALL $this->DB_NAME.dbo.FAM_UPDATE_ASIGNATURAS_PLAN(?,?,?,?)}";
                
                $stmt = $this->conn->prepare($sql);
			$stmt->bindParam(1, $id_asignatura, PDO::PARAM_INT);
                        $stmt->bindParam(2, $codigo, PDO::PARAM_STR);
                        $stmt->bindParam(3, $nombre, PDO::PARAM_STR);
                        $stmt->bindParam(4, $nivel, PDO::PARAM_INT);
			$stmt->execute();
                
            }catch(Exception $ex){
                echo 'Error en sentencia update: ' . $ex->getCode();
            }
        }
        
        //PROCESAMIENTO DE AUTETIFICACIÓN
        function FAM_CAMBIAR_CONTRASEÑA($pass_n, $user){
            try{
                $sql = "{CALL $this->DB_NAME.dbo.FAM_CAMBIAR_CONTRASENA(?,?)}";
                
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(1, $pass_n, PDO::PARAM_STR);
                $stmt->bindParam(2, $user, PDO::PARAM_INT);
                $stmt->execute();
                
                return 0;
                
            } catch (Exception $ex) {
                echo 'Error en sentencia update: ' . $ex->getCode();
            }
        }
        
        function FAM_SELECT_DATOS_USUARIO($user_id){
            try{
                $sql = "SELECT * FROM $this->DB_NAME.dbo.SGU_USUARIO WHERE ID_USUARIO = :ID_USUARIO";
                
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':ID_USUARIO', $user_id, PDO::PARAM_INT);
                $stmt->execute();
                
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                
                return $resultado;
                
            } catch (Exception $ex) {
                echo 'Error en sentencia select: ' . $ex->getCode();
            }
        }
        
        function FAM_UPDATE_OFERTA_DATOS($id_oferta, $semestre, $ano, $escuela){
            try{
                $sql = "{CALL $this->DB_NAME.dbo.FAM_UPDATE_OFERTA_DATOS(?,?,?,?)}";
                
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(1, $id_oferta, PDO::PARAM_INT);
                $stmt->bindParam(2, $semestre, PDO::PARAM_INT);
                $stmt->bindParam(3, $ano, PDO::PARAM_INT);
                $stmt->bindParam(4, $escuela, PDO::PARAM_STR);
                $stmt->execute();
                
                return 0;
                
            } catch (Exception $ex) {
                echo 'Error en sentencia update: ' . $ex->getCode();
            }
        }
        
        function FAM_UPDATE_SECCIONES_OFERTA($id_seccion, $cod_seccion, $profesor, $inscritos, $cupos, $capacidad,
                 $dia, $inicio, $termino, $modalidad){
            try{
                $sql = "{CALL $this->DB_NAME.dbo.FAM_UPDATE_SECCIONES_OFERTA(?,?,?,?,?,?,?,?,?,?)}";
                
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(1, $id_seccion, PDO::PARAM_INT);
                $stmt->bindParam(2, $cod_seccion, PDO::PARAM_STR);
                $stmt->bindParam(3, $profesor, PDO::PARAM_STR);
                $stmt->bindParam(4, $inscritos, PDO::PARAM_INT);
                $stmt->bindParam(5, $cupos, PDO::PARAM_INT);
                $stmt->bindParam(6, $capacidad, PDO::PARAM_INT);
                $stmt->bindParam(7, $dia, PDO::PARAM_STR);
                $stmt->bindParam(8, $inicio, PDO::PARAM_STR);
                $stmt->bindParam(9, $termino, PDO::PARAM_STR);
                $stmt->bindParam(10, $modalidad, PDO::PARAM_STR);
                $stmt->execute();
                
                
            } catch (Exception $ex) {
                echo 'Error en sentencia update: ' . $ex->getCode();
            }
        }
        
        function FAM_DESBLOQUEAR_USUARIO($id_usuario){
            try{
                $sql = "{CALL $this->DB_NAME.dbo.FAM_DESBLOQUEAR_USUARIO(?)}";
                
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(1, $id_usuario, PDO::PARAM_INT);
                $stmt->execute();
                
                return 0;
                
            } catch (Exception $ex) {
                echo 'Error en sentencia delete: ' . $ex->getCode();
            }
        }
        
        function FAM_BLOQUEAR_USUARIO($id_usuario){
            try{
                $sql = "{CALL $this->DB_NAME.dbo.FAM_BLOQUEAR_USUARIO(?)}";
                
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(1, $id_usuario, PDO::PARAM_INT);
                $stmt->execute();
                
                return 0;
                
            } catch (Exception $ex) {
                echo 'Error en sentencia update: ' . $ex->getCode();
            }
        }
        
        function FAM_VERIFICAR_NIVEL_MINIMO_ASIGNATURAS($cod_plan, $rut_alumno){
            try{
                $sql = "{CALL $this->DB_NAME.dbo.FAM_VERIFICAR_NIVEL_MINIMO_ASIGNATURAS(?,?)}";
                
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(1, $cod_plan, PDO::PARAM_STR);
                $stmt->bindParam(2, $rut_alumno, PDO::PARAM_STR);
                $stmt->execute();
                
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                
                return $resultado;
                
            } catch (Exception $ex) {
                echo 'Error en sentencia delete: ' . $ex->getCode();
            }
        }
        
        function FAM_SELECT_ESCUELAS(){
            try{
                $sql = "SELECT * FROM $this->DB_NAME.dbo.ESCUELAUMAYOR";
                
                $stmt = $this->conn->prepare($sql);
                $stmt->execute();
                
                $resultado = $stmt->fetchAll();
                
                return $resultado;
                
            } catch (Exception $ex) {
                echo 'Error en sentencia select: ' . $ex->getCode();
            }
        }
        
        function FAM_BORRAR_OFERTA($id_oferta){
            try{
                $sql = "{CALL $this->DB_NAME.dbo.FAM_BORRAR_OFERTA(?)}";
                
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(1, $id_oferta, PDO::PARAM_INT);
                $stmt->execute();
                
                return 0;
                
            } catch (Exception $ex) {
                echo 'Error en sentencia delete: ' . $ex->getCode();
            }
        }
        
        //PROCEDIMIENTOS PARA REPORTES
        
        function FAM_REPORT_NOREPROBACION_BY_RUT($rut,$sem,$ano){
            try{
                $sql = "{CALL $this->DB_NAME.dbo.FAM_REPORT_NOREPROBACION_BY_RUT(?,?,?)}";
                
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(1, $rut, PDO::PARAM_STR);
                $stmt->bindParam(2, $sem, PDO::PARAM_INT);
                $stmt->bindParam(3, $ano, PDO::PARAM_INT);
                $stmt->execute();
                
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                
                return $resultado;
                
            } catch (Exception $ex) {
                echo 'Error en sentencia select: ' . $ex->getCode();
            }
        }
        
        function FAM_SELECT_DOCENTES(){
            try{
                $sql = "{CALL $this->DB_NAME.dbo.FAM_SELECT_DOCENTES()}";
                
                $stmt = $this->conn->prepare($sql);
                $stmt->execute();
                $resultado = $stmt->fetchAll();
                
                return $resultado;
                
            } catch (Exception $ex) {
                echo 'Error en sentencia select: ' . $ex->getCode();
            }
        }
        
        function FAM_SELECT_CODIGOS_ASIGNATURAS_EN_SECCIONES(){
            try{
                $sql = "{CALL $this->DB_NAME.dbo.FAM_SELECT_CODIGOS_ASIGNATURAS_EN_SECCIONES()}";
                
                $stmt = $this->conn->prepare($sql);
                $stmt->execute();
                $resultado = $stmt->fetchAll();
                
                return $resultado;
                
            } catch (Exception $ex) {
                echo 'Error en sentencia select: ' . $ex->getCode();
            }
        }
        
        function FAM_REPORT_TASAAPROBADOS_GENERAL($escuela){
            try{
                $sql = "{CALL $this->DB_NAME.dbo.FAM_REPORT_TASAAPROBADOS_GENERAL(?)}";
                
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(1, $escuela, PDO::PARAM_STR);
                $stmt->execute();
                
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                
                return $resultado;
                
            } catch (Exception $ex) {
                echo 'Error en sentencia select: ' . $ex->getMessage();
            }
        }
        
        function FAM_REPORT_TASAAPROBADOS_BY_ASIGNATURA($ano,$sem,$asignatura,$escuela){
            try{
                $sql = "{CALL $this->DB_NAME.dbo.FAM_REPORT_TASAAPROBADOS_BY_ASIGNATURA(?,?,?,?)}";
                
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(1, $ano, PDO::PARAM_INT);
                $stmt->bindParam(2, $sem, PDO::PARAM_INT);
                $stmt->bindParam(3, $asignatura, PDO::PARAM_STR);
                $stmt->bindParam(4, $escuela, PDO::PARAM_STR);
                $stmt->execute();
                
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                
                return $resultado;
                
            } catch (Exception $ex) {
                echo 'Error en sentencia select: ' . $ex->getMessage();
            }
        }
        
        function FAM_REPORT_TASAAPROBADOS_BY_DOCENTE($ano,$sem,$docente,$escuela){
            try{
                $sql = "{CALL $this->DB_NAME.dbo.FAM_REPORT_TASAAPROBADOS_BY_DOCENTE(?,?,?,?)}";
                
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(1, $ano, PDO::PARAM_INT);
                $stmt->bindParam(2, $sem, PDO::PARAM_INT);
                $stmt->bindParam(3, $docente, PDO::PARAM_STR);
                $stmt->bindParam(4, $escuela, PDO::PARAM_STR);
                $stmt->execute();
                
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                
                return $resultado;
                
            } catch (Exception $ex) {
                echo 'Error en sentencia select: ' . $ex->getMessage();
            }
        }
        
        function FAM_UPDATE_ALUMNO_PRACTICA($rut, $estado, $comentarios, $estado2, $comentarios2){
            
            try{
                $sql = "{CALL $this->DB_NAME.dbo.FAM_UPDATE_ALUMNO_PRACTICA(?,?,?,?,?)}";

                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(1, $rut, PDO::PARAM_STR);
                $stmt->bindParam(2, $estado, PDO::PARAM_STR);
                $stmt->bindParam(3, $comentarios, PDO::PARAM_STR);
                $stmt->bindParam(4, $estado2, PDO::PARAM_STR);
                $stmt->bindParam(5, $comentarios2, PDO::PARAM_STR);
                $stmt->execute();
			
            }catch(PDOException $ex){
                echo 'Error en sentencia update: ' . $ex->getMessage();
            }
        }
        
        function FAM_VINCULAR_HOMOLOGACION($inicial, $adicional){
            
            try{
                $sql = "{CALL $this->DB_NAME.dbo.FAM_VINCULAR_HOMOLOGACION(?,?)}";

                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(1, $inicial, PDO::PARAM_STR);
                $stmt->bindParam(2, $adicional, PDO::PARAM_STR);
                $stmt->execute();
			
            }catch(PDOException $ex){
                echo 'Error en sentencia insert: ' . $ex->getMessage();
            }
        }
        
        function FAM_ID_ASIGNATURA_BY_CODIGO($codigo){
            
            try{
                $sql = "SELECT ID_ASIGNATURA FROM $this->DB_NAME.dbo.ASIGNATURA WHERE COD_ASIGNATURA = :COD_ASIGNATURA";
			
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':COD_ASIGNATURA', $codigo, PDO::PARAM_STR);
                $stmt->execute();

                $result = $stmt->fetchAll();
                $ID_ASIGNATURA = $result[0]['ID_ASIGNATURA'];
                
                return $ID_ASIGNATURA;
                
            } catch (PDOException $ex) {
                echo 'Error en sentencia select: ' . $ex->getMessage();
            }
        }
        
        function FAM_SELECT_PLANES_ESCUELA($ESCUELA){
            
            try{
                $sql = "{CALL $this->DB_NAME.dbo.FAM_SELECT_PLANES_ESCUELA(?)}";

                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(1, $ESCUELA, PDO::PARAM_STR);
                $stmt->execute();
                
                $result = $stmt->fetchAll();
                
                return $result;
			
            }catch(PDOException $ex){
                echo 'Error en sentencia select: ' . $ex->getMessage();
            }
        }
        
        function FAM_SELECT_HOMOLOGACIONES_ASIGNATURA($id_asig){
            
            try{
                $sql = "{CALL $this->DB_NAME.dbo.FAM_SELECT_HOMOLOGACIONES_ASIGNATURA(?)}";

                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(1, $id_asig, PDO::PARAM_INT);
                $stmt->execute();
                
                $result = $stmt->fetchAll();
                
                return $result;
			
            }catch(PDOException $ex){
                echo 'Error en sentencia select: ' . $ex->getMessage();
            }
        }
        
        function FAM_BORRAR_HOMOLOGACION($id_inicial, $id_adicional){
            try{
                $sql = "{CALL $this->DB_NAME.dbo.FAM_BORRAR_HOMOLOGACION(?,?)}";
                
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(1, $id_inicial, PDO::PARAM_INT);
                $stmt->bindParam(2, $id_adicional, PDO::PARAM_INT);
                $stmt->execute();
                
                return 0;
                
            } catch (Exception $ex) {
                echo 'Error en sentencia delete: ' . $ex->getCode();
            }
        }
        
        function FAM_SELECT_ASIGNATURAS_PLUS_BY_RUT($rut){
            try{
                $sql = "{CALL $this->DB_NAME.dbo.FAM_SELECT_ASIGNATURAS_PLUS_BY_RUT(?)}";
                
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(1, $rut, PDO::PARAM_STR);
                $stmt->execute();
                
                $result = $stmt->fetchAll();
                
                return $result;
                
            } catch (Exception $ex) {
                echo 'Error en sentencia select: ' . $ex->getCode();
            }
        }
        
        
        
        
        
        
        
}

?>

