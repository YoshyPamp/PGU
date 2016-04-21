<?php

    foreach (glob("../../clases/*.php") as $filename){
        include $filename;
    }
    
    $db = new Database();
    
    if(isset($_GET['codigo_plan'])){
        $datos_plan = $db->FAM_SELECT_PLAN_ESTUDIO($_GET['codigo_plan']);
        if(!empty($datos_plan)){
            echo json_encode($datos_plan);
        }else{
            echo '{ "error": "Plan no existe para ese código." }';
        } 
    }

