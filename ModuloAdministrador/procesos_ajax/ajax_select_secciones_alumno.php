<?php

    foreach (glob("../../clases/*.php") as $filename){
        include $filename;
    }
    
    $db = new Database();
    
    if(isset($_GET['rut'])){
        $rut = $_GET['rut'];
        $secciones = $db->FAM_SELECT_SECCIONES_ALUMNO_ALL($rut);
        
        echo json_encode($secciones);
    }
