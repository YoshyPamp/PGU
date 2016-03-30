<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

date_default_timezone_set('America/Santiago');

//Busca una seccion en horario con dia y modulo
    function buscaSec($dia, $mod, $horario) {
        $existe = null;
        foreach ($horario as $sec) {
            if (utf8_encode($sec['DIA']) == $dia && $sec['INICIO'] == $mod[0]) {
                $existe = $sec;
            }
        }
        return $existe;
    } 
    
    //Calcula en que semestre estamos
    $fecha_limite_sem1 = date('m-d', strtotime('08/01'));
    date('m-d') < $fecha_limite_sem1 ? $sem = 1 : $sem = 2;
    
    //Arreglo con días de la semana
    $dias_semana = array(
        'Lunes',
        'Martes',
        'Miércoles',
        'Jueves',
        'Viernes',
        'Sábado'
    );
    
    //Arreglo con modulos
    $modulos = array(
        '1' => array('8:00:00','9:30:00'),
        '2' => array('9:40:00','11:10:00'),
        '3' => array('11:20:00','12:50:00'),
        '4' => array('13:50:00','15:20:00'),
        '5' => array('15:30:00','17:00:00'),
        '6' => array('17:10:00','18:40:00'),
        '7' => array('18:50:00','20:00:00'),
        '8' => array('20:05:00','21:15:00'),
        '9' => array('21:20:00','22:30:00'),
    );

?>
