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
    
    $debug = new helpers();
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
			$db->FAM_PLAN_INSERT($codigo, $tipo, $facultad, $escuela, $sede,
            $grado_bach, $grado_acad, $titulo, $nombre, $jornada, $duracion);
		
			foreach($_POST as $value){
				if(is_array($value)){
					$db->FAM_ASIGNATURA_INSERT($codigo, $value[2], $value[0], $value[1]);
				}
			}
			echo "<script>window.location='index.php?imported=ok'</script>";
		}else{
			echo "<script>window.location='index.php?imported=error'</script>";
		}
    }
	
	//Procesamiento para Acta
	if(isset($_POST['hola'])){
		
	}
	
	//Procesamiento para Oferta
	if(isset($_POST['año'])){
		$debug->ecc($_POST);
	}
	
	?>
	
    