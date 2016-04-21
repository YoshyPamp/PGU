<?php

    foreach (glob("../../clases/*.php") as $filename){
        include $filename;
    }
    
    $db = new Database();

    if(isset($_POST['codigo_plan'])){
        
        $codigo_plan = $_POST['codigo_plan'];
        $nombre = utf8_decode($_POST['nombre']);
        $grd_bac = utf8_decode($_POST['grd_bac']);
        $grd_aca = utf8_decode($_POST['grd_aca']);
        $titulo = utf8_decode($_POST['titulo']);
        $tipo = $_POST['tipo'];
        $duracion = $_POST['duracion'];
        
        try{
            $db->FAM_UPDATE_DATOS_PLAN($codigo_plan, $nombre, $grd_bac, $grd_aca, $titulo, $tipo, $duracion);
                
            $msg = "<div class='container alert alert-success'>Plan Actualizado Satisfactoriamente.</div>";
            echo $msg;
            
        } catch (Exception $ex) {
            $msg = "<div class='container alert alert-danger'>Error al actualizar plan.</div>";
            echo $msg;
        }
        
    }else{ 
        $msg = "<div class='container alert alert-danger'>Error en envío de parametros.</div>";
        echo $msg;
    }

