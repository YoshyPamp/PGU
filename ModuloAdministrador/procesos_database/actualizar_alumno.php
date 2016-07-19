<?php 
    foreach (glob("../../clases/*.php") as $filename){
        include $filename;
    }
    
    $db = new Database();

    if(isset($_POST['rut_alumno'])){
        
        $rut = $_POST['rut_alumno'];
        $nombres = $_POST['nombre'];
        $estado = $_POST['estado'];
        $matricula = $_POST['matricula'];
        $correo = $_POST['email'];
        
        try{
            $db->FAM_UPDATE_ALUMNO($rut, $matricula, $nombres, $estado, $correo);
                
            $msg = "<div class='container alert alert-success'>Alumno Actualizado Satisfactoriamente.</div>";
            header("Location: ../admin_alumnos.php?msg='".$msg."'");
            
        } catch (Exception $ex) {
            $msg = "<div class='container alert alert-danger'>Error al actualizar el alumno.</div>";
            header("Location: ../admin_alumnos.php?msg='".$msg."'");
        }
        
    }else{ 
        $msg = "<div class='container alert alert-danger'>Error en envío de parametros.</div>";
        header("Location: ../admin_alumnos.php?msg='".$msg."'");
    }