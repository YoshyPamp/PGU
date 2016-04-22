<?php

    foreach (glob("../../clases/*.php") as $filename){
        include $filename;
    }
    
    $db = new Database();
    
    //var_dump($_POST);
    try{
        foreach($_POST as $key => $variable):
            if($key == "ano_oferta"){
                $ano_oferta = $variable;
            }else{
                if($key == "semestre_oferta"){
                    $sem_oferta = $variable;
                }else{
                    if($key == "codigo_oferta"){
                        $cod_ofe = $variable;
                    }else{
                        $id_seccion = $key;
                        $cod_seccion = $variable[0];
                        $profesor = utf8_decode($variable[1]);
                        $inscritos = $variable[2];
                        $cupos = $variable[3];
                        $capacidad = $variable[4];
                        $dia = utf8_decode($variable[5]);
                        $inicio = $variable[6];
                        $termino = $variable[7];
                        $modalidad = $variable[8];
                        
                        $db->FAM_UPDATE_SECCIONES_OFERTA($id_seccion, $cod_seccion, $profesor, $inscritos,
                                $cupos, $capacidad, $dia, $inicio, $termino, $modalidad);
                    }
                }
            }
        endforeach;
        
        if($db->FAM_UPDATE_OFERTA_DATOS($cod_ofe, $sem_oferta, $ano_oferta) == 0){
            $msg = "<div class='container alert alert-success'>Oferta Actualizada Satisfactoriamente.</div>";
            header("Location: ../admin_ofertas.php?msg='".$msg."'");
        }else{
            $msg = "<div class='container alert alert-danger'>Error al actualizar la oferta.</div>";
            header("Location: ../admin_ofertas.php?msg='".$msg."'");
        }
        
    } catch (Exception $ex) {
        $msg = "<div class='container alert alert-danger'>Error al actualizar la oferta.</div>";
        header("Location: ../admin_ofertas.php?msg='".$msg."'");
    }

