<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Decreto
 *
 * @author joshe.onate
 */
class Decreto {
    
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
        //Posteriormente Guarda en un indice llamado "cantidad" el numero de paginas que había y lo retorna.
        foreach($pages as $page){
            $aux_page = utf8_encode($page->getText());
            $paginas[] = $aux_page;
            $num_paginas++;
        }
        $paginas["cantidad"] = $num_paginas;
        return $paginas;
    }
    
    public function sacarDatosHeader($decreto){
        //Separa la pagina en lineas
        $header_lineas = explode("\n",$decreto);
        
        //Crea una instancia de objeto plan
        $plan = new Plan();
        $plan->setTipo_plan($header_lineas[2]);
        
        //Separa la linea 4 en un arreglo con facultad, escuela y sede
        $facu_escu_sede = explode("ESCUELA",$header_lineas[3]);
        
        $plan->setFacultad(substr($facu_escu_sede[0],33,53));
        
        //Separa el campo 3 del arreglo anterior en escuela y sede
        $escu_sede = explode("SEDE",$facu_escu_sede[2]);
        $plan->setEscuela(substr($escu_sede[0], 1,100));
        $plan->setSede(substr($escu_sede[1], 2,20));
        
        $plan->setGrado_bach(substr($header_lineas[4], 19,100));
        $plan->setGrado_acad(substr($header_lineas[5], 18,100));
        $plan->setTitulo(substr($header_lineas[6], 21,100));
        
        //Separa el campo 8 en nombre plan y codigo plan
        $plan_codigo = explode("  PLAN ESTUDIO",$header_lineas[8]);
        
        $plan->setNombre(substr($plan_codigo[0], 14,50));
        $plan->setCodigo(substr($plan_codigo[1], 51,80));
        $plan->setJornada($header_lineas[10]);
        
        //Separa campo 10 en espacios para tomar jornada y duración
        $jornada_duracion = explode(" ",$header_lineas[10]);
        
        $plan->setJornada($jornada_duracion[2]);
        $plan->setDuracion_sem($jornada_duracion[3]);
        
        return $plan;
    }
    
    public function mapearPaginas($doc){
        
        $decreto = $this->leerpaginas($doc);
        
        //Envía sólo la primera página para leer datos de header, que serán los del plan
        $plan = $this->sacarDatosHeader($decreto[0]);
        //Se crea un arreglo generico que almacenara todas las asignaturas que tiene el plan.
        $plan_asignaturas = array();
        $plan_con_ramos = array();
        $plan_con_ramos['DATOS'] = $plan;
        //Contador que se utilizara para solo tomar las asignaturas hasta nivel maximo y no las electivas.
        $contador_nivel = 1;
        
        //Recorre cada página del decreto y va creando una asignatura y agregandola en el arreglo general
        for($i = 0;$i < $decreto['cantidad'];$i++){
            //Deja solo el cuerpo con las asignaturas de cada página.
            $decreto_body = substr($decreto[$i], 1220);
            
            //Separa el string por \n y los guarda en un arreglo
            $pagina_body_espacios = explode("\n",$decreto_body);

            //Revisa todas las lineas que partan con un numero de nivel y los guarda en un arreglo que solo contendra asignaturas
            $asignaturas_linea = array();
            foreach($pagina_body_espacios as $linea){
                if(preg_match("/[0-9][0-9]\s/", substr($linea,0,3))){
                    $asignaturas_linea[] = $linea;
                }
            }
            unset($pagina_body_espacios);
            
            //Por cada linea crea un ramo y asignarle el campo correspondiente
            foreach($asignaturas_linea as $asignatura){
                
                //Solo si el nivel esta entre el primero y el ultimo para no tomar los ramos electivos
                //if(intval(substr($asignatura,0,2)) >= $contador_nivel){
                    $ramo = new AsignaturaPlan();
                    $ramo->setNivel(substr($asignatura,0,2));
                    $ramo->setCodigo(substr($asignatura,3,8));
                
                    //Algunos nombres vienen con basura despues de la S, esto lo corta y deja solo el nombre.
                    $nombre = explode(" S ",substr($asignatura,12));

                    $ramo->setNombre($nombre[0]);
                    $decreto_lineas[] = $ramo;
                    $contador_nivel = intval(substr($asignatura,0,2));
                //}
            }
        }
        $plan_con_ramos['RAMOS'] = $decreto_lineas;
		
        return $plan_con_ramos;
    }
}
