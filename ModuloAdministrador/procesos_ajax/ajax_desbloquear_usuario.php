<?php

    foreach (glob("../../clases/*.php") as $filename){
        include $filename;
    }
    
    $db = new Database();
    if(isset($_GET['id'])){
        $id_usuario = $_GET['id'];
        
        if($db->FAM_DESBLOQUEAR_USUARIO($id_usuario) == 0){
            echo "Usuario desbloqueado.";
        }else{
            echo "Error al Desbloquear Cuenta de Usuario.";
        }
        
    }

