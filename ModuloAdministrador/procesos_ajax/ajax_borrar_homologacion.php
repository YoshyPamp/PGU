<?php

    foreach (glob("../../clases/*.php") as $filename){
        include $filename;
    }
    
    $db = new Database();
    if(isset($_GET['id_inicial'],$_GET['id_adicional'])){
        $id_inicial = $_GET['id_inicial'];
        $id_adicional = $_GET['id_adicional'];
        
        if($db->FAM_BORRAR_HOMOLOGACION($id_inicial, $id_adicional) == 0){
            echo "Homologación borrada.";
        }else{
            echo "Error al borrar homologación.";
        }
        
    }