<?php

    foreach (glob("../../clases/*.php") as $filename){
        include $filename;
    }
    
    $db = new Database();

    if(isset($_GET['cod_sec'], $_GET['ano'], $_GET['semestre'])){
        $cod_sec = $_GET['cod_sec'];
        $ano = $_GET['ano'];
        $semestre = $_GET['semestre'];
        $alumnos = $db->FAM_SELECT_ALUMNOS_SECCION($cod_sec, $ano, $semestre);
        
        
        echo json_encode($alumnos);
    }