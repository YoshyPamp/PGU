<?php

    foreach (glob("../../clases/*.php") as $filename){
        include $filename;
    }
    
    $db = new Database();
    
    if(isset($_GET['codigo_plan'])){
        $asignaturas_plan = $db->FAM_SELECT_ASIGNATURAS_PLAN($_GET['codigo_plan'],'proyeccion');
        if(!empty($asignaturas_plan)){
            
            foreach($asignaturas_plan as $key => $asignatura){
                if(fnmatch("FG[A-T][A-L][0-9]*",$asignatura['COD_ASIGNATURA'])){
                    unset($asignaturas_plan[$key]);
                }
            }
            echo json_encode($asignaturas_plan);
        }
    }