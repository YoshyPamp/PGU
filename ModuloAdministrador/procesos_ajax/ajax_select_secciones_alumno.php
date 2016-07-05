<?php

    foreach (glob("../../clases/*.php") as $filename){
        include $filename;
    }
    
    $db = new Database();

    if(isset($_GET['rut'])){
        $rut = $_GET['rut'];
        $secciones = $db->FAM_SELECT_SECCIONES_ALUMNO_ALL($rut);
        
        foreach($secciones as $key => $seccion){
            $secciones[$key]['PROFESOR_NOMBRE'] = utf8_encode($seccion['PROFESOR_NOMBRE']);
        }
        echo json_encode($secciones);
    }