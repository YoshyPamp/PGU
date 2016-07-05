<?php

    foreach (glob("../../clases/*.php") as $filename){
        include $filename;
    }

    $db = new Database();
    
    $rut = $_GET['rut'];
    
    $resultado = $db->FAM_SELECT_SECCIONES_BYRUT($rut);
    
    $ramos_rendidos = array();
    $con = 0;
    foreach($resultado as $asignatura){
        $ramos_rendidos[$con]['COD_SECCION'] = $asignatura['COD_SECCION'];
        $ramos_rendidos[$con]['NOM_ASIGNATURA'] = utf8_encode($asignatura['NOM_ASIGNATURA']);
        $ramos_rendidos[$con]['ANO'] = $asignatura['ANO'];
        $ramos_rendidos[$con]['SEMESTRE'] = $asignatura['SEMESTRE'];
        $ramos_rendidos[$con]['NOTA'] = round($asignatura['NOTA'],1);
        $ramos_rendidos[$con]['NIVEL'] = $asignatura['NIVEL'];
        if($asignatura['NOTA'] >= 4){
            $ramos_rendidos[$con]['ESTADO'] = 'APROBADO';
        }else{
            $ramos_rendidos[$con]['ESTADO'] = 'REPROBADO';
        }
        $con++;
    }
    
    echo json_encode($ramos_rendidos);