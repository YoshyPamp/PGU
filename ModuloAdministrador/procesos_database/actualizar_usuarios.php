<?php
    
    foreach (glob("../../clases/*.php") as $filename){
        include $filename;
    }
    
    $db = new Database();
    
    foreach($_POST as $key => $usuario){
        
        try{
            if($key != 'usuarios_length'){
                $id_usuario = $key;
                $usuario_n = $usuario[0];
                $mail = $usuario[1];
                $rut_alumno = $usuario[2];
                $perfil = $usuario[3];

                $db->FAM_UPDATE_USUARIOS($id_usuario, $usuario_n, $mail, $rut_alumno, $perfil);
            }
            
        } catch (Exception $ex) {
            $msg = "<div class='container alert alert-danger'>Error al actualizar usuarios.</div>";
            header("Location: ../admin_usuarios.php?msg='".$msg."'");
        }
        
        $msg = "<div class='container alert alert-success'>Usuarios Actualizados Satisfactoriamente.</div>";
        header("Location: ../admin_usuarios.php?msg='".$msg."'");
    }
    
    


