<?php

    foreach (glob("../../clases/*.php") as $filename){
        include $filename;
    }
    
    $db = new Database();
    
    if(isset($_GET['codigo_plan'])){
        $asignaturas_plan = $db->FAM_SELECT_ASIGNATURAS_PLAN($_GET['codigo_plan']);
        if(!empty($asignaturas_plan)){
            echo json_encode($asignaturas_plan);
        }
    }