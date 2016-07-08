<?php

    if(isset($_POST['tit'], $_POST['com'])){
        $titulo = utf8_decode($_POST['tit']);
        $comentario = utf8_decode($_POST['com']);
    }

    // El mensaje
    $mensaje = "COMENTARIO: \r\n\n";
    $mensaje .= $comentario;

    // Si cualquier línea es más larga de 70 caracteres, se debería usar wordwrap()
    $mensaje = wordwrap($mensaje, 70, "\r\n");

    // Enviarlo
    if(mail('joshe.onate@umayor.cl', $titulo, $mensaje)){
        echo "Reporte enviado satisfactoriamente.";
    }else{
        echo "Error al enviar reporte de error. Contacte al departamento de sistemas.";
    }
    
    

