<?php

foreach (glob("../../clases/*.php") as $filename){
    include $filename;
}

$db = new Database();

$comentarios = $_POST['comentario'];
$estado = $_POST['estado'][0];
$rut = $_POST['rutalumno'];


try{

    $db->FAM_UPDATE_ALUMNO_PRACTICA($rut, $estado, $comentarios);

    $msg = "<div class='container alert alert-success'>Alumno Actualizado Satisfactoriamente.</div>";
    header("Location: ../admin_cent_practica.php?msg='".$msg."'");
            
} catch (Exception $ex) {
    $msg = "<div class='container alert alert-danger'>Error al actualizar el alumno.</div>";
    header("Location: ../admin_cent_practica.php?msg='".$msg."'");
}

