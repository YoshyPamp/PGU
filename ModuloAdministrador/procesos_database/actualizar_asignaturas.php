<?php

    foreach (glob("../../clases/*.php") as $filename){
        include $filename;
    }
    
    $db = new Database();
    
    try{
        foreach($_POST as $key => $variable):
            if($key == 'codigo_plan_hidden'){
                $codigo_plan = $variable;
            }else{
                $db->FAM_UPDATE_ASIGNATURAS_PLAN($key, $variable[0], utf8_decode($variable[1]), $variable[2]);

            }
        endforeach;
        $msg = "<div class='container alert alert-success'>Asignaturas Actualizadas Satisfactoriamente.</div>";
        header("Location: ../admin_planesdeestudio.php?msg='".$msg."'");
        
    } catch (Exception $ex) {
        $msg = "<div class='container alert alert-danger'>Error al actualizar las asignaturas de plan.</div>";
        header("Location: ../admin_planesdeestudio.php?msg='".$msg."'");
    }
    
