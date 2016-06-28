<?php

    foreach (glob("../../clases/*.php") as $filename){
        include $filename;
    }

    $db = new Database();
    
    $rut = $_GET['rut'];
    
    $alumno = $db->FAM_SELECT_ALUMNO_RUT($rut);
    $nivel = $db->FAM_VERIFICAR_NIVEL_MINIMO_ASIGNATURAS($alumno['CODIGO_PLAN'], $rut);
    $nivel_minimo = $nivel['NIVEL_MINIMO'];
    $alumno['NIVEL'] = $nivel_minimo;
    echo json_encode($alumno);