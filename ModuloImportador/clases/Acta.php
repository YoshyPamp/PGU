<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Acta
 *
 * @author YoshyPamp
 */
class Acta {
    
    private $parser;
    
    function __construct() {
        $this->parser = new \Smalot\PdfParser\Parser();
    }    
    
    public function leerPaginas($doc){
        
        $pdf = $this->parser->parseFile($doc);
        $pages = $pdf->getPages();
        
        $num_paginas = 0;
        $paginas = array();
        //Recorre todas las paginas y graba cada una en un indice del arrego $paginas.
        //Posteriormente Guarda en un indice llamado "cantidad" el numero de paginas que hab�a y lo retorna.
        foreach($pages as $page){
            $aux_page = $page->getText();
            $paginas[] = $aux_page;
            $num_paginas++;
        }
        $paginas["cantidad"] = $num_paginas;
        return $paginas;
    }
    
    public function separarActa($acta){
        $headbody = explode("ALUMNO", $acta);
        $headboy[0] = str_replace(" ", "",$headbody[0]);
        $headboy[1] = str_replace(" ", "",$headbody[1]);
        return $headbody;
    }
    
    public function mapearPaginas($doc){
        
        $resultado;
        
        $acta = $this->leerpaginas($doc);
        /**
         * Recorre la acta pagina por pagina
         * Genera 3 arreglos globales: Alumnos, Asignaturas y un arreglo que combine los 2 mas las notas.
         */
        $alumnos_arreglo = array();
        $asignaturas_arreglo = array();
        $alumnos_asignatura_notas = array();
        for($i = 0;$i < $acta['cantidad'];$i++){
            
            //Separa acta en head y body
            $acta_separada = $this->separarActa($acta[$i]);
            $header = $acta_separada[0];
            $body = $acta_separada[1];
           
            //Separa header y body por lineas y las guarda en arreglos respectivos
            $header_lineas = explode("\n",$header);
            $body_lineas = explode("\n",$body);
            unset($body_lineas[0]);
            unset($body_lineas[count($body_lineas)]);
            unset($body_lineas[count($body_lineas)]);
   
            //Pregunta si pagina es continuaci�n de otra asignatura o es principio
            if(substr($body_lineas[1],0,1) == 1){
                $asignatura = new Asignatura();
                //Genera un string con a�o y semestre juntos
                $año_semestre = substr($header_lineas[1], 11);

                //Guarda en asignatura los datos dados
                $asignatura->setAño(substr($año_semestre, 0,4));
                $asignatura->setSemestre(substr($año_semestre, -1));
                $asignatura->setCodigo(substr($header_lineas[4], -12,8));
                $asignatura->setSeccion(substr($header_lineas[4], -3));
                $asignatura->setNombre(substr($header_lineas[3], 48));
                $asignatura->setProfesor(substr($header_lineas[5], 25));
                
                $asignaturas_arreglo[$asignatura->getCodigo()." ".$asignatura->getSeccion()] = $asignatura;
            }else{
                //Si es continuaci�n asigna asignatura segun codigo y secci�n a un objeto temporal
                $asignatura_temporal = $asignaturas_arreglo[substr($header_lineas[4], -12,8)." ".substr($header_lineas[4], -3)];
                $asignaturas_arreglo[$asignatura_temporal->getCodigo()." ".$asignatura_temporal->getSeccion()] = $asignatura;
            } 
            
            /**
            * Recorre body de pagina alumno por alumno
            * Ademas trae arreglo temporal con los alumnos de esa asignatura
            */
           $cod_asignatura = substr($header_lineas[4], -12,8);
           $sec_asignatura = substr($header_lineas[4], -3); 
           $arreglo_alumnos_temporal = $asignaturas_arreglo[$cod_asignatura." ".$sec_asignatura]->getAlumnos();
           for($j = 1;$j <= count($body_lineas);$j++){
               $alumno = new Alumno();
               $notas_arreglo = array();
               //Separa string de alumno por espacios y lo guarda en arreglo body temporal
               $body_temporal = explode(" ",$body_lineas[$j]);
               
               //Pregunta si el campo que corresponde al rut tiene nombre por ser alumno con nombre compuesto
               if(ctype_alpha($body_temporal[4])){
                   
               }

               $alumno->setApellidoMaterno(substr($body_temporal[2],0,  strlen($body_temporal[2])-1));
               $alumno->setApellidoPaterno($body_temporal[1]);
               $alumno->setNombre($body_temporal[3]);
               $alumno->setRut($body_temporal[4]);
               $alumno->setPlan($body_temporal[6]);
               $alumnos_arreglo[$alumno->getRut()] = $alumno;
               
               //Elimina indices en blanco y reemplaza . por , para poder realizar calculos, ademas elimina los datos que no sean notas
               $body_temporal = str_replace(",", ".",$body_temporal);
               $body_temporal = array_filter($body_temporal);
               //Chequea si el alumno tiene NA
               $esNA = in_array("NA", $body_temporal);
               //Recorre arreglo para eliminar textos y dejar notas
               foreach ($body_temporal as $key => $value) {
                    if (!is_numeric($value)) {
                        unset($body_temporal[$key]);
                    }
                }
			
               //Elimina numero de alumno y rut
               $body_temporal = array_values($body_temporal);
			   
               unset($body_temporal[0]);
			   
			   //Si el campo del rut corresponde a una nota por el hecho de que el rUT haya sido -K, no lo elimina
			   if(count($body_temporal) != 0){
					if(strlen($body_temporal[1]) != 3){
						unset($body_temporal[1]);
					}
			   }
			   
               
               $body_temporal = array_values($body_temporal);
               
               //Chequea si alumno tiene notas, sino guarda directamente el rut del alumno con el estado NA
               if($esNA){
                   $arreglo_alumnos_temporal[] = array(
                       "notas" => $notas_arreglo,
                       "estado" => "NA",
                       "rut" => $alumno->getRut());
               }else{
                   //Recorre todas las notas y ponderaciones
                   $porcentajes = 0;
                   for($z = 0;$z < count($body_temporal);$z++){
                       $nota = new Nota();
                       $nota->setNota($body_temporal[$z]);
                       $z++;
                       //chequea si nota no tiene ponderaci�n por ser nota final
                       if($z < count($body_temporal)){
                           $nota->setNota_ponderada($body_temporal[$z]);
                           $porcentaje_num = round(($nota->getNota_ponderada()/$nota->getNota())*100);
                           $porcentaje = $porcentaje_num."%";
                           
                           $porcentajes += $porcentaje_num;
                           
                           $nota->setPorcentaje($porcentaje);
                           if($nota->getPorcentaje() == "70%"){
                               $nota->setTipo("NP");
                           }else{
                               if($porcentajes == 200){
                                   $nota->setTipo("EX");
                               }else{
                                   $nota->setTipo("P");
                               }
                           }
                       }else{
                           $nota->setNota_ponderada(0);
                           $nota->setPorcentaje("100%");
                           $nota->setTipo("NF");
                       }
                       $notas_arreglo[] = $nota;
                   }
                   $arreglo_alumnos_temporal[] = array(
                       "notas" => $notas_arreglo,
                       "estado" => "C",
                       "rut" => $alumno->getRut());
               }
               //Setea nuevamente al arreglo principal de asignaturas el listado modificado de alumnos y notas
               $asignaturas_arreglo[$cod_asignatura." ".$sec_asignatura]->setAlumnos($arreglo_alumnos_temporal);    
            }
        }
        $resultado = array(
            "asignaturas" => $asignaturas_arreglo,
            "alumnos"     => $alumnos_arreglo
        );
        return $resultado;
    }
}
        
        
    
    


