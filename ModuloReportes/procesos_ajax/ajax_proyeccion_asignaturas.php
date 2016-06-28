<?php

    foreach (glob("../../clases/*.php") as $filename){
        include $filename;
    }

    $db = new Database();
    
    $rut = $_GET['rut'];
    
    $alumno = $db->FAM_SELECT_ALUMNO_RUT($rut);
    $ramos = $db->FAM_SELECT_SECCIONES_ALUMNO_ALL($rut);
    $asignaturas = $db->FAM_SELECT_ASIGNATURAS_PLAN($alumno['CODIGO_PLAN'],'proyeccion');
    
    foreach($asignaturas as $key => $asignatura){
        foreach($ramos as $ramo){
            if($asignatura['COD_ASIGNATURA'] == substr($ramo['COD_SECCION'],0,-4)){
                unset($asignaturas[$key]);
            }
        }
        if(fnmatch("FG[A-T][A-L][0-9]*",$asignatura['COD_ASIGNATURA'])){
            unset($asignaturas[$key]);
        }
    }
    
    echo json_encode($asignaturas);