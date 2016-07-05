<?php

foreach (glob("../../clases/*.php") as $filename){
        include $filename;
    }
    
    $db = new Database();
    if(isset($_GET['inicial']) && isset($_GET['adicional'])){
        
        $asignaturas_adicionales = json_decode($_GET['adicional']);
        $asignatura_inicial = $_GET['inicial'];
        $id_inicial = $db->FAM_ID_ASIGNATURA_BY_CODIGO($asignatura_inicial);
        
        $homologaciones = count($asignaturas_adicionales);
        foreach($asignaturas_adicionales as $value){
            $id = $db->FAM_ID_ASIGNATURA_BY_CODIGO($value);
            $db->FAM_VINCULAR_HOMOLOGACION($id_inicial,$id);
            $homologaciones--;
        }
        
        if($homologaciones == 0){
            echo "Homologación Realizada.";
        }else{
            echo "Error en registrar homologación de asignaturas.";
        }
    }