<?php

    foreach (glob("../../clases/*.php") as $filename){
        include $filename;
    }
    
    $db = new Database();
    if(isset($_GET['ano']) && isset($_GET['sem'])){
        $secciones = $db->FAM_SELECT_OFERTA($_GET['ano'], $_GET['sem']);
        if($secciones != ''){
            echo json_encode($secciones);
        }else{
            echo "{'error': 'No existe oferta para esa busqueda.'}";
        }
    }   
