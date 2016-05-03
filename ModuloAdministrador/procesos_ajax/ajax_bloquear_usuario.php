<?php

    foreach (glob("../../clases/*.php") as $filename){
        include $filename;
    }
    
    $db = new Database();
    if(isset($_GET['id'])){
        $id_usuario = $_GET['id'];
        
        if($db->FAM_BLOQUEAR_USUARIO($id_usuario) == 0){
            echo "Usuario bloqueado.";
        }else{
            echo "Error al Bloquear Cuenta de Usuario.";
        }
        
    }

