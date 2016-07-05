<?php

    foreach (glob("../../clases/*.php") as $filename){
        include $filename;
    }

    $db = new Database();
    
    $rut = $_GET['rut'];
    
    $homologadas = $db->FAM_SELECT_ASIGNATURAS_PLUS_BY_RUT($rut);
    
    $asignaturas = array();
    
    foreach($homologadas as $key => $ramo){
        $asignaturas[$key]['PLANESTUDIO_COD_PLANESTUDIO'] = $ramo['PLANESTUDIO_COD_PLANESTUDIO'];
        $asignaturas[$key]['COD_SECCION'] = $ramo['COD_SECCION'];
        $asignaturas[$key]['NOM_ASIGNATURA'] = utf8_encode($ramo['NOM_ASIGNATURA']);
        $asignaturas[$key]['PROFESOR_NOMBRE'] = utf8_encode($ramo['PROFESOR_NOMBRE']);
        $asignaturas[$key]['ANO'] = $ramo['ANO'];
        $asignaturas[$key]['SEMESTRE'] = $ramo['SEMESTRE'];
        $asignaturas[$key]['NIVEL'] = $ramo['NIVEL'];
        $asignaturas[$key]['NOTA'] = round($ramo['NOTA'],1);
        $asignaturas[$key]['ESTADO'] = $ramo['ESTADO'];
    }
    
    echo json_encode($asignaturas);
    
    