<?php

    foreach (glob("../../clases/*.php") as $filename){
        include $filename;
    }

    $db = new Database();
    
    $plan = $_GET['plan'];
    $ano = $_GET['ano'];
    $semestre = $_GET['semestre'];
    
    if($plan == ''){
        $homologadas = $db->FAM_SELECT_ASIGNATURAS_PLUS_BY_OFERTA($ano, $sem);
    }else{
        $homologadas = $db->FAM_SELECT_ASIGNATURAS_PLUS_BY_PLAN($plan, $ano, $sem);
    }
    
    $asignaturas = array();
    
    foreach($homologadas as $key => $ramo){
        $asignaturas[$key]['PLANESTUDIO_COD_PLANESTUDIO'] = $ramo['PLANESTUDIO_COD_PLANESTUDIO'];
        $asignaturas[$key]['COD_SECCION'] = $ramo['COD_SECCION'];
        $asignaturas[$key]['NOM_ASIGNATURA'] = utf8_encode($ramo['NOM_ASIGNATURA']);
        $asignaturas[$key]['PROFESOR_NOMBRE'] = utf8_encode($ramo['PROFESOR_NOMBRE']);
        $asignaturas[$key]['ANO'] = $ramo['ANO'];
        $asignaturas[$key]['SEMESTRE'] = $ramo['SEMESTRE'];
    }
    
    echo json_encode($asignaturas);
    
    