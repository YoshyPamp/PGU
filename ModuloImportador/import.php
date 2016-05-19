<?php
	ini_set('xdebug.var_display_max_depth', -1);
	ini_set('xdebug.var_display_max_children', -1);
	ini_set('xdebug.var_display_max_data', -1);

	error_reporting(E_ALL);


    foreach (glob("clases/*.php") as $filename)
    {
        include $filename;
    }
    foreach (glob("../clases/*.php") as $filename)
    {
        include $filename;
    }
    
    $db = new Database();
    
    //$debug->ecc($_POST);
    
	//Procesamiento para Decreto
    if(isset($_POST['grado_bach'])){
        $codigo = $_POST['codigo'];
        $tipo = $_POST['tipo'];
        $facultad = $_POST['facultad'];
        $escuela = $_POST['escuela'];
        $sede = $_POST['sede'];
        $grado_bach = $_POST['grado_bach'];
        $grado_acad = $_POST['grado_acad'];
        $titulo = $_POST['titulo'];
        $nombre = $_POST['nombre'];
        $jornada = $_POST['jornada'];
        $duracion = $_POST['duracion'];
		
		$existe = $db->VERIFICAR_PLAN_EXISTENTE($codigo);
		
		if(!$existe){
			$db->FAM_PLAN_INSERT($codigo, $tipo, utf8_decode($facultad), utf8_decode($escuela), $sede,
            utf8_decode($grado_bach), utf8_decode($grado_acad), utf8_decode($titulo), utf8_decode($nombre), $jornada, $duracion);
			
			foreach($_POST as $value){
				if(is_array($value)){
					$db->FAM_ASIGNATURA_INSERT($codigo, utf8_decode($value[2]), $value[0], $value[1]);
				}
			}
			echo "<script>window.location='index.php?imported=ok'</script>";
		}else{
			echo "<script>window.location='index.php?imported=error'</script>";
		}
    }
	
	//Procesamiento para Acta
	
	$alumnos_malos = array('nada' => '1-1'); //Arreglo que vincula alumno malo con el rut que le corresponde
	$alumnos_insertados = array();
	$ramos_sin_oferta = array();
	$con_nada = 0;
	$id_seccion = 'nada';
	$acta_sin_notas = true;
        
	if(isset($_POST['acta'])){
		foreach($_POST as $key => $arreglo_interno){
			//No toma el primer campo ya que es auxiliar.
			if($key != 'acta' && $key != 'vacia'){
				//Para filtrar entre importe de alumnos y de notas
                                
				//ALUMNOS
				if(substr($key,0,6) == 'alumno'){
					//realiza un proceso si es de los que venían con problemas.
					if(!is_numeric(substr($key,7,8))){
						$alumnos_malos[] = $arreglo_interno[3]."-".substr($key,7,100);
					}
					
					$nom = $arreglo_interno[0];
					$ap1 = $arreglo_interno[1];
					$ap2 = $arreglo_interno[2];
					$rut = $arreglo_interno[3];
					$pln = $arreglo_interno[4];
					
					if($db->FAM_INSERT_ALUMNO($nom, $ap1, $ap2, $rut, $pln)){
						$alumnos_insertados[] = $nom." ".$ap1." ".$ap2;
					}
					
				}else{
					if(!isset($_POST['vacia'])){
                                             	//NOTAS
						$datos = explode('-',$key);
                                                
                                                //alumnos que vienen malos
						if(!is_numeric($datos[0])){
							foreach($alumnos_malos as $alumno){
								$rut_id = explode("-",$alumno);
								if($rut_id[1] == $datos[0]){
									$datos[0] = $rut_id[0];
								}
							}
						}
						
                                                if(count($datos) > 1){
                                                    
                                                    $rut_alum = $datos[0];
                                                    $cod_ramo = $datos[1];
                                                    $_seccion = $datos[2];
                                                    $ano_ramo = $datos[3];
                                                    $sem_ramo = $datos[4];
                                                    $estado_A = $datos[5];

                                                    switch($db->VERIFICAR_NOTAS_EXISTENTES($rut_alum, $ano_ramo, $sem_ramo, $cod_ramo, $_seccion)){
                                                        case 0:
                                                            $cantd_notas = count($arreglo_interno);
                                                            $notas = 0;

                                                            while($notas < $cantd_notas){
                                                                    $nota = $arreglo_interno[$notas];
                                                                    $pond = $arreglo_interno[$notas+1];
                                                                    $porc = $arreglo_interno[$notas+2];
                                                                    $tipo = $arreglo_interno[$notas+3];
                                                                    $notas = $notas + 4;
                                                              
                                                                    $id_seccion = $db->FAM_INSERT_NOTA($rut_alum, $cod_ramo, $_seccion, $ano_ramo, $sem_ramo,
                                                                                                                    $nota, $pond, $porc, $tipo);
                                                                    if($id_seccion == null){
                                                                            if(!in_array($cod_ramo."-".$_seccion,$ramos_sin_oferta)){
                                                                                    $ramos_sin_oferta[] = $cod_ramo."-".$_seccion;
                                                                            }
                                                                    }
                                                            }

                                                            $con_nada++;
                                                            break;
                                                        case -1:
                                                            $mensaje = 'YA EXISTEN NOTAS PARA ALGUNAS SECCIONES DE ESA OFERTA, DEBE BORRARLAS DESDE EL ADMINISTRADOR Y VOLVER A IMPORTAR.';
                                                            break;
                                                        case -2:
                                                            $mensaje = 'ALGUNA SECCION NO EXISTE EN OFERTA PARA ESE AÑO';
                                                            break;
                                                    }
                                                    $acta_sin_notas = false;
                                                }else{
                                                    //Para los alumnos sin notas en acta
                                                    $rut_alum = $datos[0];
                                                    
                                                    $ramo = explode("-",$arreglo_interno);
                                                    $cod_ramo = $ramo[0];
                                                    $_seccion = $ramo[1];
                                                    $ano_ramo = $ramo[2];
                                                    $sem_ramo = $ramo[3];
                                                    
                                                    $db->FAM_INSERT_NOTA($rut_alum, $cod_ramo, $_seccion, $ano_ramo, $sem_ramo, 0, 0, 0, "S/N");
                                                }
                                                
					}else{
						//Procesamiento cuando la acta viene vacía de notas
						$datos = explode('-',$arreglo_interno);

						$codigo = $datos[0];
						$seccio = $datos[1];
						$año    = $datos[2];
						$sem    = $datos[3];
                                                
                                                foreach($alumnos_malos as $alumno_malo){
                                                    $datos_ = explode("-",$alumno_malo);
                                                    if($datos_[1] == $key){
                                                        $rut = $datos_[0];
                                                    }else{
                                                        $rut  = $key;
                                                    }
                                                }
						
						
						if($db->FAM_VINCULAR_ALUM_SECC($codigo, $seccio, $año, $sem, $rut) == -1){
							if(!in_array($codigo."-".$seccio, $ramos_sin_oferta)){
								$ramos_sin_oferta[] = $codigo."-".$seccio;
							}
						}
					}
				}
			}
		}
		
		if($id_seccion == 'nada' && $con_nada == 0){
                    if($acta_sin_notas){
                            if(count($ramos_sin_oferta) == 0){
                                    echo "<script>window.location='index.php?acta=ok&num=".count($alumnos_insertados)."&notas=sin'</script>";
                            }else{
                                    $ramos_sin_oferta = serialize($ramos_sin_oferta);
                                    echo "<script>window.location='index.php?acta=ok&num=".count($alumnos_insertados)."&notas=sin&sinoferta=".$ramos_sin_oferta."'</script>";
                            }
                    }else{
                            echo "<script>window.location='index.php?acta=ok&num=".count($alumnos_insertados)."&notas=on&men=".$mensaje."'</script>";
                    }

		}else{
                    if(count($ramos_sin_oferta) >= 1){
                            $ramos_sin_oferta = serialize($ramos_sin_oferta);
                            echo "<script>window.location='index.php?acta=ok&num=".count($alumnos_insertados)."&notas=yes&ramos=".$ramos_sin_oferta."'</script>";
                    }else{
                            echo "<script>window.location='index.php?acta=ok&num=".count($alumnos_insertados)."&notas=ok200'</script>";
                    }
		}
	}
	
	
	//Procesamiento para Oferta
	try {
		$asignaturas_malas = array();
		
		if(isset($_POST['año'])){
			//$debug->ecc($_POST);
			$año = $_POST['año'];
			$semestre = $_POST['semestre'];
			$total = count($_POST) - 2;
                        $escuela = $_POST['escuela'];
			
			if(!$db->VERIFICAR_OFERTA_EXISTENTE($año, $semestre, $escuela)){
				$id_oferta = $db->FAM_OFERTA_INSERT($año, $semestre, $escuela);
		
				for($i = 1; $i <= $total; $i++) {
					//VARIABLES POR ASIGNATURA
                                    if(isset($_POST[$i][0])){
                                        $nombre = $_POST[$i][0];
					$codigo = $_POST[$i][1];
					$seccion = $_POST[$i][2];
					$profesor = $_POST[$i][3];
					$inscritos = $_POST[$i][5];
					$cupos = $_POST[$i][6];
					$capacidad = $_POST[$i][7];
					$sala = $_POST[$i][11];
					$dia = $_POST[$i][10];
					$inicio = $_POST[$i][8];
                                        $inicio = substr($inicio, 0, -3);
					$termino = $_POST[$i][9];
                                        $termino = substr($termino, 0, -3);
					$modalidad = $_POST[$i][4];
				
					$id_seccion = $db->FAM_SECCION_INSERT($codigo, $seccion, utf8_decode($profesor), $id_oferta, $inscritos, $cupos, $capacidad, $sala,
															utf8_decode($dia), $inicio, $termino, $modalidad);
															
					
					if($id_seccion != 0){
						
					}else{
						$asignaturas_malas[] = $codigo;
					}
                                    }
				}
			}else{
				echo "<script>window.location='index.php?imported=errorO'</script>";
			}
			
			if(count($asignaturas_malas) == 0){
				echo "<script>window.location='index.php?imported=ok'</script>";
			} else {
				$asignaturas_malas = serialize($asignaturas_malas);
				echo "<script>window.location='index.php?imported=ramoX&malas=".$asignaturas_malas."'</script>";
			}
		}	
	}
	catch(Exception $e) {
		echo "<script>console.log($e);</script>";
	    echo "<script>window.location='index.php?imported=error'</script>";
	}
	
	
	
	?>
	
    