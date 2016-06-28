<?php 

    foreach (glob("../../clases/*.php") as $filename){
        include $filename;
    }

    $db = new Database();

    $año = $_GET['ano'];
    $semestre = $_GET['semestre'];
    $docente = $_GET['docente'];
    $asignatura = $_GET['asig'];
    $escuela = $_GET['escu'];

    if($docente == null){
        $TASAS = $db->FAM_REPORT_TASAAPROBADOS_BY_ASIGNATURA($año, $semestre, $asignatura, $escuela);
        echo json_encode($TASAS);
    }else{
        if($asignatura == null){
            $TASAS = $db->FAM_REPORT_TASAAPROBADOS_BY_DOCENTE($año, $semestre, $docente, $escuela);
            echo json_encode($TASAS);
        }
    }

?>