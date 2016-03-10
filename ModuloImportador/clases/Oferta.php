<?php

	/** Include path **/
	set_include_path(get_include_path() . PATH_SEPARATOR . '../librerias/PHPExcel-1.8/Classes');

	/** PHPExcel_IOFactory */
	include 'PHPExcel/IOFactory.php';

	/*
	 * To change this license header, choose License Headers in Project Properties.
	 * To change this template file, choose Tools | Templates
	 * and open the template in the editor.
	 */

	/**
	 * Description of Oferta
	 *
	 * @author joshe.onate
	 */
	class Oferta {
		
		private $excelOBJ;
		
		function __construct() {
			$this->excelOBJ = new MyReadFilter(1,1000,range('A','Z'));
		}
		
		function convertXLStoCSV($infile,$outfile) {
				$fileType = PHPExcel_IOFactory::identify($infile);
				$objReader = PHPExcel_IOFactory::createReader($fileType);
			 
				$objReader->setReadDataOnly(true);   
				$objPHPExcel = $objReader->load($infile);    
			 
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
				$objWriter->save($outfile);
		}
		
		public function leerPaginas($inputFileName){
			
			//Usage:
			$this->convertXLStoCSV($inputFileName,'oferta.csv');
			 
			/**  Identify the type of $inputFileName  **/
			$inputFileType = PHPExcel_IOFactory::identify('oferta.csv');


			
			/**  Create a new Reader of the type defined in $inputFileType  **/ 
			$objReader = PHPExcel_IOFactory::createReader($inputFileType); 
			/**  Tell the Reader that we want to use the Read Filter  **/ 
			$objReader->setReadFilter($this->excelOBJ); 
			/**  Load only the rows and columns that match our filter to PHPExcel  **/ 
			$objPHPExcel = $objReader->load($inputFileName); 
			
			$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
			
			$resultado = array();
			
			foreach($sheetData as $asignatura){
				$OA = new OfertaAsignatura();
				
				if($asignatura['C'] != null && $asignatura['C'] != false && $asignatura['C'] != 'Paq.(abr.)'){
					$OA->setNombre($asignatura['B']);
				
					$cod_sec = explode("-",$asignatura['C']);
					
					$OA->setCodigo($cod_sec[0]);
					$OA->setSeccion($cod_sec[1]);
					
					$OA->setProfesor($asignatura['I']);
					$OA->setModalidad($asignatura['D']);
					$OA->setCapacidad($asignatura['E']);
					$OA->setLibres($asignatura['F']);
					$OA->setInscritos($asignatura['G']);
					$OA->setInicio($asignatura['J']);
					$OA->setTermino($asignatura['K']);
					
					if($asignatura['M'] == 'X') {
						$OA->setDia("Lunes");
					}elseif ($asignatura['O'] == 'X') {
						$OA->setDia("Martes");
					}elseif ($asignatura['Q'] == 'X') {
						$OA->setDia("Miércoles");
					}elseif ($asignatura['S'] == 'X') {
						$OA->setDia("Jueves");
					}elseif ($asignatura['U'] == 'X') {
						$OA->setDia("Viernes");
					}elseif ($asignatura['W'] == 'X') {
						$OA->setDia("Sábado");
					}else{
						$OA->setDia(null);
					}
					
					
					$OA->setSala($asignatura['X']);
					
					$resultado[] = $OA;
				}
				
			}
			
			return $resultado;
			
		} 
	}

	
	//Clase que permite filtrar que celdas y filas leer del excel con PHPExcel
	class MyReadFilter implements PHPExcel_Reader_IReadFilter 
	{ 
		private $_startRow = 0; 
		private $_endRow   = 0; 
		private $_columns  = array(); 

		/**  Get the list of rows and columns to read  */ 
		public function __construct($startRow, $endRow, $columns) { 
			$this->_startRow = $startRow; 
			$this->_endRow   = $endRow; 
			$this->_columns  = $columns; 
		} 

		public function readCell($column, $row, $worksheetName = '') { 
			//  Only read the rows and columns that were configured 
			if ($row >= $this->_startRow && $row <= $this->_endRow) { 
				if (in_array($column,$this->_columns)) { 
					return true; 
				} 
			} 
			return false; 
		} 
	} 
	
	
