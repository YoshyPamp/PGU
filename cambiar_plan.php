<?php

    foreach (glob("clases/*.php") as $filename){
        include $filename;
    }

    $db = new Database();
    
    $estado_antiguo = $_POST['estado'];
    $plan_nuevo = $_POST['plan'];
    $ano_nuevo = $_POST['ano'];
    $rut = $_POST['rut'];
    $plan_antiguo = $_POST['plan_actual'];
    $ano_antiguo = $_POST['ano_actual'];
    
    if($db->FAM_UPDATE_PLAN_ACTUAL_ALUMNO($estado_antiguo, $plan_nuevo, $ano_nuevo, $rut, $plan_antiguo, $ano_antiguo)){
    }else{
        echo "Error en actualizar el plan de estudio.";
    }
    
    
