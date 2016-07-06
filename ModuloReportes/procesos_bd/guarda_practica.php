<?php

    foreach (glob("../../clases/*.php") as $filename){
        include $filename;
    }

    $db = new Database();

    $comentarios1 = $_POST['comentario'];
    $comentarios2 = $_POST['comentario2'];
    $estado = $_POST['estado'];
    $estado2 = $_POST['estado2'];
    $rut = $_POST['rutalumno'];


    try{

        $db->FAM_UPDATE_ALUMNO_PRACTICA($rut, $estado, $comentarios1, $estado2, $comentarios2);
        if($estado = 'CURSADA'){
            
        }

        $msg = "<div class='container alert alert-success'>Alumno Actualizado Satisfactoriamente.</div>";
        header("Location: ../admin_cent_practica.php?msg='".$msg."'");

    } catch (Exception $ex) {
        $msg = "<div class='container alert alert-danger'>Error al actualizar el alumno.</div>";
        header("Location: ../admin_cent_practica.php?msg='".$msg."'");
    }

