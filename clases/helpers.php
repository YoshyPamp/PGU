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
        '1' => array('8:00','9:30'),
        '2' => array('9:40','11:10'),
        '3' => array('11:20','12:50'),
        '4' => array('13:50','15:20'),
        '5' => array('15:30','17:00'),
        '6' => array('17:10','18:40'),
        '7' => array('18:50','20:00'),
        '8' => array('20:05','21:15'),
        '9' => array('21:20','22:30'),
    );

?>
