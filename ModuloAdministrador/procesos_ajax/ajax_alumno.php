<?php
    foreach (glob("../../clases/*.php") as $filename){
        include $filename;
    }
    
    $db = new Database();
    if(isset($_GET['rut'])){
        $datos_alumno = $db->FAM_SELECT_ALUMNO_RUT($_GET['rut']);
        if(!empty($datos_alumno)){
            echo json_encode($datos_alumno);
        }else{
            echo '{ "error": "Alumno no existe para ese rut." }';
        }      
    }     

