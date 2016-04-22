<?php

    foreach (glob("../../clases/*.php") as $filename){
        include $filename;
    }
    
    $db = new Database();
    
    if(isset($_POST['pass1'], $_POST['pass2'], $_POST['id_us'])){
        $pass_old = $_POST['pass1'];
        $pass_new = $_POST['pass2'];
        $user = $_POST['id_us'];
        
        $password_new = password_hash($pass_new, PASSWORD_DEFAULT);
        
        $resultado_user = $db->FAM_SELECT_DATOS_USUARIO($user);
        
        if(password_verify($pass_old, $resultado_user['CONTRASENA'])){
            $result = $db->FAM_CAMBIAR_CONTRASEÑA($password_new, $user);
            echo 0;
        }else{
            echo -1;
        }
    }

