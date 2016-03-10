<?php
    foreach (glob("clases/*.php") as $filename)
    {
        include $filename;
    }
    foreach (glob("../clases/*.php") as $filename)
    {
        include $filename;
    }
	
	ini_set('xdebug.var_display_max_depth', -1);
	ini_set('xdebug.var_display_max_children', -1);
	ini_set('xdebug.var_display_max_data', -1);

    
	$db = new Database();
    $debug = new helpers();
    require 'vendor/autoload.php';
	
	
	if(isset($_GET['imported'])){
		if($_GET['imported'] == 'ok'){
			$importe_exitoso = "IMPORTE DE INFORMACIÓN EXITOSA";
		}else{
			$importe_exitoso = "ERROR : PLAN DE ESTUDIO YA EXISTE CON ESE CÓDIGO, ELIMINAR ANTERIOR Y VOLVER A IMPORTAR.";
		}
	}
    
?>


<!DOCTYPE html>
<html lang="es-Es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><-- Sistema de Gestión Universitaria --></title>
    <!-- Bootstrap Core CSS -->
    <link href="../librerias/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="../librerias/bootstrap/js/jquery.js"></script>
    <link href="//cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="../librerias/bootstrap/js/bootstrap.min.js"></script>
    <script src="//cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
    <script src="../js/datatables.js"></script>
    <script src='../js/helpers.js'></script>
    <!-- Custom CSS -->
    <link href="../css/styles.css" rel="stylesheet">
</head>
<script type="text/javascript">
    $(window).load(function() {
        $(".loader").fadeOut("slow");
    })
    

</script>
<style>
    .accordion-toggle{
        cursor: pointer;
            
    }
</style>
<body>
    <div class="loader"></div>
    <nav class="navbar navbar-default nvb">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand logo" href="../index.php">
                    <img alt="Brand"  src="../Imagenes/logo-U.jpg">
                </a>
            </div>
            <h1 class="col-md-8">SISTEMA DE GESTIÓN UNIVERSITARIO</h1>
        </div>
    </nav>

    <div class="container">
		<?php
			if(isset($_GET['imported'])){
				if($_GET['imported'] == "ok"){
					echo "<div class='alert alert-success' role='alert'>".$importe_exitoso."</div>";
				}else{
					echo "<div class='alert alert-danger' role='alert'>".$importe_exitoso."</div>";
				}
			}
		
		
		?>
        <form class="form-signin" method="post" action="upload.php" enctype="multipart/form-data">
        <h2 class="form-signin-heading">Favor ingresar documento</h2>
        <input type="file" name="fileToUpload" id="inputFile" required><br>
        <select class="form-control" name="tipo" required>
            <option value="">Seleccione tipo de documento...</option>
            <option value="1">Acta</option>
            <option value="2">Decreto</option>
			<option value="3">Oferta</option>
        </select><br>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Leer Documento</button>
      </form>
        <br>
        
        <?php
        
        /**
         * INICIO DE RESUMEN DE DATOS
         */
            if(isset($_GET['upload'])){
                echo "<div class='alert alert-success' role='alert'>Su documento ha sido leído satisfactoriamente.</div>";
                unset($_GET['ok']);
                if($_GET['tipo'] == 1){
                    /**
                     * INICIO PROCESO ACTA
                     */   
                    
                    $acta = new Acta();
                    $resultado_acta = $acta->mapearPaginas("Documentos/".$_GET['name']);
                    $total_alumnos = count($resultado_acta['alumnos']);
                    $total_asignaturas = count($resultado_acta['asignaturas']);
                
                    //Contadores por modificaciones    
                    $conAlumnos = 0;
                    $conNotas = 0;
                    $conA = 1;
                    $conAs = 1;
                    ?>
                    <form method="post" action="import.php" id='importar'>
                        <input type='submit' value='IMPORTAR DATOS' class='btn btn-success btn-lg center-block col-md-12' style='display:hidden' /><br><br>
        
                        <h3 class='col-md-12 text-center' >RESUMEN DE DATOS A IMPORTAR <small><em>Presione IMPORTAR cuando este seguro de los datos.</em></small></h3><br><br><br><br>
            
                        <h3 style='color: green'>Alumnos a Importar <strong>(<?php echo $total_alumnos; ?>)</strong></h3>
                        <table class='table table-hover' >
                            <thead>
                                <tr class='info'>
                                    <th class='center'>N°</th>
                                    <th class='center'>Nombre</th><th class='center'>Apellido Paterno</th><th class='center'>Apellido Materno</th><th class='center'>Rut</th>
                                    <th class='center'>Plan</th><th class='center'>Importar</th>
                                </tr>
                                <tr class='accordion-toggle success' data-toggle="collapse" data-target=".demo1"><th colspan='7' class='center'>Presione aquí para ocultar o expandir.</th></tr>
                            </thead>
                
                            <tbody class="accordian-body collapse demo1">
                                <?php foreach($resultado_acta['alumnos'] as $alumno): ?>
                                <tr>
                                    <!-- VERIFICA SI ALUMNO LLEGO CON PROBLEMAS POR NOMBRE O APELLIDO COMPUESTO-->
                                    <?php if(!preg_match('#[0-9]#',$alumno->rut)): ?>
                                        <td class='center'><?php echo $conA; ?></td>
                                        <td class='center'><input type='text' name='alumnoNom<?php echo $conAlumnos; ?>' style='background-color: lightsteelblue' placeholder='<?php echo $alumno->nombre; ?>' required></td>
                                        <td class='center'><input type='text' name='alumnoApePat<?php echo $conAlumnos; ?>' style='background-color: lightsteelblue;' placeholder='<?php echo $alumno->apellidoPaterno; ?>' required></td>
                                        <td class='center'><input type='text' name='alumnoApeMat<?php echo $conAlumnos; ?>' style='background-color: lightsteelblue;' placeholder='<?php echo $alumno->apellidoMaterno; ?>' required></td>
                                        <td class='center'><input type='text' name='alumnoRut<?php echo $conAlumnos; ?>' style='background-color: lightsteelblue;' placeholder='<?php echo $alumno->rut; ?>' required></td>
                                        <td class='center'><input type='text' name='alumnoPlan<?php echo $conAlumnos; $conAlumnos++; ?>' style='background-color: lightsteelblue;' placeholder='<?php echo $alumno->plan; ?>' required></td>
                                        <td class='center'><label class='text-danger'>
                                                <a href="#alumnoRut<?php echo $conAlumnos; ?>" id="alumnoRut<?php echo $conAlumnos; ?>back"><img width='20%' height='19%' src='../Imagenes/new-go-down.png'></a>
                                                <a target='_blank' href="Documentos/<?php echo $_GET['name']; ?>"><img width='20%' height='19%' src='../Imagenes/Pdf.png'></a>
                                            </label></td>
                                    <?php else: ?>
                                        <td class='center'><?php echo $conA; ?></td>
                                        <td class='center'><?php echo $alumno->nombre; ?></td>
                                        <td class='center'><?php echo $alumno->apellidoPaterno; ?></td>
                                        <td class='center'><?php echo $alumno->apellidoMaterno; ?></td>
                                        <td class='center'><?php echo $alumno->rut; ?></td>
                                        <td class='center'><?php echo $alumno->plan; ?></td>
                                        <td class='center'><input name='<?php echo $alumno->rut; ?>' type='checkbox' checked /></td>
                                    <?php endif; ?>
                                </tr>
                                <?php $conA++;?>
                                <?php endforeach;?>

                            </tbody>
                        </table>
        
        
        
                        <h3 style='color: green'>Asignaturas a Importar <strong>(<?php echo $total_asignaturas; ?>)</strong></h3>
                        <table class='table table-hover table-striped' id='example_alumnos'>
                            <tr class='accordion-toggle success' data-toggle="collapse" data-target=".demo2"><th colspan='8' class='center'>Presione aquí para ocultar o expandir.</th></tr>  
                            <thead>   
                                <tr class='info'>
                                    <th class='center' >N</th>
                                    <th class='center' >Código</th>
                                    <th class='center' >Sección</th>
                                    <th class='center' >Nombre</th>
                                    <th class='center' >Profesor</th>
                                    <th clasS='center' >Año</th>
                                    <th class='center' >Semestre</th>
                                    <th class='center' >Importar</th>
                                </tr>
                            </thead>    
                                <?php foreach($resultado_acta['asignaturas'] as $asignatura): ?>
                            
                            <tbody class="accordian-body collapse demo2">
                                <tr>
                                    <td class='center' data-toggle="tooltip" data-placement="top" data-container="body" title="N°"><?php echo $conAs; ?></td>
                                    <td class='center' data-toggle="tooltip" data-placement="top" data-container="body" title="Código"><?php echo $asignatura->codigo; ?></td><td class='center' data-toggle="tooltip" data-container="body" data-placement="top" title="Sección"><?php echo $asignatura->seccion; ?></td>
                                    <td class='center' data-toggle="tooltip" data-placement="top" data-container="body" title="Nombre"><?php echo $asignatura->nombre; ?></td><td class='center' data-toggle="tooltip" data-container="body" data-placement="top" title="Profesor"><?php echo $asignatura->profesor; ?></td>
                                    <td class='center' data-toggle="tooltip" data-placement="top" data-container="body" title="Año"><?php echo $asignatura->año; ?></td><td class='center' data-toggle="tooltip" data-container="body" data-placement="top" title="Semestre"><?php echo $asignatura->semestre; ?></td>
                                    <td class='center' data-toggle="tooltip" data-placement="top" data-container="body" title="Importar"><input type='checkbox' checked name="<?php echo $asignatura->codigo ." ". $asignatura->seccion; ?>[]" /></td>
                                </tr>
                                <tr class="info"><td colspan='8' class='center'><strong>ALUMNOS</strong> (Presionar columna estado para notas.)</td></tr>
                                    <?php foreach($asignatura->alumnos as $notas): ?>
                                        <tr class="info">
                                            <th class='center' colspan='3'>Rut</th><th class='center' colspan='5'>Estado</th>
                                        </tr>
                                        <?php if(!preg_match('#[0-9]#',$notas['rut'])): ?>
                                        <tr class="info">
                                                <td colspan='3' class="center">
                                                    <input type='text' id="alumnoRut<?php $conNotas++; echo $conNotas; ?>" name='alumnoNotRut<?php echo $conNotas; ?>' style='background-color: lightsteelblue;' placeholder='<?php echo $notas['rut']; ?>' required>
                                                    <a href="#alumnoRut<?php echo $conNotas; ?>back"><img src='../Imagenes/old-go-up.png' widht='22%' height="22%"></a>
                                                </td>
                                            <?php else: ?>
                                            <tr class="info">
                                                <td class="center" colspan='3'><?php echo $notas['rut']; ?></td>
                                                <input type='hidden' value='<?php echo $notas['rut']; ?>' name="<?php echo $asignatura->codigo ." ". $asignatura->seccion; ?>[]">
                                            <?php endif; ?>
                                            <td colspan='5' class="center accordion-toggle" data-target=".nota<?php echo $notas['rut']; ?> " data-toggle="collapse"><?php echo $notas['estado']; ?></td>
                                            <tr class="info accordion-toggle collapse info nota<?php echo $notas['rut']; ?> ">
                                                <th class='center' colspan='2'>Nota</th><th colspan='2' class='center'>Ponderación</th>
                                                <th class='center' colspan='2'>Porcentaje</th><th class='center' colspan='2'>Tipo</th>
                                            </tr>
                                            <?php foreach($notas['notas'] as $nota): ?>
                                            <tr class="accordion-toggle collapse nota<?php echo $notas['rut']; ?> ">
                                                <td class='center' colspan='2'><?php echo $nota->nota; ?></td>
                                                <td class='center' colspan='2'><?php echo $nota->nota_ponderada; ?></td>
                                                <td class='center' colspan='2'><?php echo $nota->porcentaje; ?></td>
                                                <td class='center' colspan='2'><?php echo $nota->tipo; ?></td>
                                            </tr>
                                            <?php endforeach;?>
<!--                                            <tr class="info accordion-toggle info ">
                                                <th class='center' colspan='2'>Nota</th><th colspan='2' class='center'>Ponderación</th>
                                                <th class='center' colspan='2'>Porcentaje</th><th class='center'>Tipo</th>
                                            </tr>-->
                                        </tr>
                                    <?php endforeach;?>
                                        </tbody>
                                    <?php $conA++;?>
                                <?php endforeach;?>

                        </table>
                        <br>
                        <input type='hidden' name='archivo' value='Documentos/<?php echo $_GET['name']; ?>' />
                        <input type='hidden' name='conAlumnos' value='<?php echo $conAlumnos; ?>' />
                    </form>
                    <?php
                    
                    /**
                     * FIN PROCESO ACTA
                     */
                }else{
					if($_GET['tipo'] == 2){
						
					/**
                     * INICIO PROCESO DECRETO
                     */   
                    
                    $decreto = new Decreto();
                    $resultado_decreto = $decreto->mapearPaginas("Documentos/".$_GET['name']);
					$existe = $db->VERIFICAR_PLAN_EXISTENTE($resultado_decreto['DATOS']->getCodigo());
                    
                    ?>
                    <form method="post" action="import.php" id='importar'>
					<?php if(!$existe):?>
                        <input type='submit' value='IMPORTAR DATOS' class='btn btn-success btn-lg center-block col-md-12' style='display:hidden' /><br><br>
					<?php else:?>
						<div class='alert alert-danger' role='danger'>PLAN CON CODIGO <?php echo $resultado_decreto['DATOS']->getCodigo(); ?> YA EXISTE EN BD, SI DESEA ACTUALIZARLO DEBE BORRAR EL QUE YA EXISTE EN EL MODULO DE ADMINISTRADOR, Y LUEGO VOLVER A REALIZAR LA IMPORTACIÓN.</div>
					<?php endif;?>
        
                        <h3 class='col-md-12 text-center' >RESUMEN DE DATOS A IMPORTAR <small><em>Presione IMPORTAR cuando este seguro de los datos.</em></small></h3><br><br><br><br>
            
                        <fieldset>
                            <legend style='color: green'>Plan de Estudio</legend>
                            <label for="tipo">Tipo de Plan: </label>
                            <input type="text" name="tipo" value="<?php echo $resultado_decreto['DATOS']->getTipo_plan(); ?>" class="form-control" required>
                            <label for="facultad">Facultad: </label>
                            <input type="text" name="facultad" value="<?php echo $resultado_decreto['DATOS']->getFacultad(); ?>" class="form-control" required>
                            <label for="escuela">Escuela: </label>
                            <input type="text" name="escuela" value="<?php echo $resultado_decreto['DATOS']->getEscuela(); ?>" class="form-control" required>
                            <label for="sede">Sede: </label>
                            <input type="text" name="sede" value="<?php echo $resultado_decreto['DATOS']->getSede(); ?>" class="form-control" required>
                            <label for="grado_bach">Grado Bachiller: </label>
                            <input type="text" name="grado_bach" value="<?php echo $resultado_decreto['DATOS']->getGrado_bach(); ?>" class="form-control" required>
                            <label for="grado_acad">Grado Académico: </label>
                            <input type="text" name="grado_acad" value="<?php echo $resultado_decreto['DATOS']->getGrado_acad(); ?>" class="form-control" required>
                            <label for="titulo">Título Profesional: </label>
                            <input type="text" name="titulo" value="<?php echo $resultado_decreto['DATOS']->getTitulo(); ?>" class="form-control" required>
                            <label for="nombre">Nombre Plan: </label>
                            <input type="text" name="nombre" value="<?php echo $resultado_decreto['DATOS']->getNombre(); ?>" class="form-control" required>
                            <label for="codigo">Código: </label>
                            <input type="text" name="codigo" value="<?php echo $resultado_decreto['DATOS']->getCodigo(); ?>" class="form-control" pattern="[A-Z]{3}[0-9]{3}[-][0-9]{4}" data-toggle="tooltip" data-placement="left" data-container="body" title="Ej: ICC120-2015" required>
                            <label for="jornada">Jornada: </label>
                            <input type="text" name="jornada" value="<?php echo $resultado_decreto['DATOS']->getJornada(); ?>" class="form-control" required>
                            <label for="duracion">Duración: </label>
                            <input type="text" name="duracion" value="<?php echo $resultado_decreto['DATOS']->getDuracion_sem(); ?>" class="form-control" required>
                        </fieldset>
                        <br>
                        <fieldset>
                            <legend style='color: green'>Asignaturas del Plan de Estudio</legend>
                            <table class='table table-hover table-striped'>
                                <thead>
                                    <tr>
                                        <th>Nivel</th><th>Código</th><th>Nombre Asignatura</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($resultado_decreto['RAMOS'] as $ramo): ?>
                                    <tr>
                                        <td>
                                            <input type="text" name="<?php echo $ramo->getCodigo(); ?>[]" class="form-control" required value="<?php echo $ramo->getNivel(); ?>" >
                                        </td>
                                        <td>
                                            <input type="text" name="<?php echo $ramo->getCodigo(); ?>[]" class="form-control" required value="<?php echo $ramo->getCodigo(); ?>" >
                                        </td>
                                        <td>
                                            <input type="text" name="<?php echo $ramo->getCodigo(); ?>[]" class="form-control" required value="<?php echo $ramo->getNombre(); ?>" >
                                        </td>
                                    </tr>
                                    <?php endforeach;?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Nivel</th><th>Código</th><th>Nombre Asignatura</th>
                                    </tr>
                                </tfoot>											
                            </table>
                        </fieldset>
                    </form>
                    
                    <?php
                        //echo "<pre>"; var_dump($resultado_decreto); echo "</pre>";
                    /**
                     * FIN PROCESO DECRETO
                     */
					}else{
						if($_GET['tipo'] == 3){
							/**
							* INICIO PROCESO OFERTA
							*/
							$inputFileName = "Documentos/".$_GET['name'];
							$oferta = new Oferta();
							$resultado_oferta = $oferta->leerPaginas($inputFileName);
							
							?>
							
							
							<form method="post" action="import.php" id='importar'>
					
								<input type='submit' value='IMPORTAR DATOS' class='btn btn-success btn-lg center-block col-md-12' style='display:hidden' /><br><br>
							
								<h3 class='col-md-12 text-center' >RESUMEN DE DATOS A IMPORTAR <small><em>Presione IMPORTAR cuando este seguro de los datos.</em></small></h3><br><br><br><br>
					
								<legend style='color: green'>Oferta Académica (<?php echo count($resultado_oferta); ?>)</legend>
								<label for='año'>Año</label>
								<input type='text' name='año' class='form-control' placeholder='2015' size='1' required>
								<label for='semestre'>Semestre</label>
								<input type='text' name='semestre' class='form-control' placeholder='2' size='1' required><br>
								<table class="table table-striped">
									<thead>
										<tr>
											<th>Número</th><th>Nombre</th><th>Código</th><th>Sección</th><th>Profesor</th><th>Modalidad</th><th>Capacidad</th><th>Cupos</th><th>Inscritos</th><th>Inicio</th><th>Término</th><th>Día</th><th>Sala</th>
										</tr>
									</thead>
									<tbody>
									<?php $con = 1;?>
									<?php foreach($resultado_oferta as $key => $asignatura): ?>
										<tr>
											<td><?php echo $con; ?></td>
											<td><input type='text' name='<?php echo $key; ?>[]' value='<?php echo $asignatura->getNombre(); ?>' class="form-control" placeholder='Nombre Curso...' /></td>
											<td><input type='text' name='<?php echo $key; ?>[]' value='<?php echo $asignatura->getCodigo(); ?>' class="form-control" required /></td>
											<td><input type='text' name='<?php echo $key; ?>[]' value='<?php echo $asignatura->getSeccion(); ?>' size='1' class="form-control" required /></td>
											<td><input type='text' name='<?php echo $key; ?>[]' value='<?php echo $asignatura->getProfesor(); ?>' class="form-control"/></td>
											<td><input type='text' name='<?php echo $key; ?>[]' value='<?php echo $asignatura->getModalidad(); ?>' class="form-control" required/></td>
											<td><input type='text' name='<?php echo $key; ?>[]' value='<?php echo $asignatura->getCapacidad(); ?>' size='1' class="form-control" /></td>
											<td><input type='text' name='<?php echo $key; ?>[]' value='<?php echo $asignatura->getLibres(); ?>' size='1' class="form-control" /></td>
											<td><input type='text' name='<?php echo $key; ?>[]' value='<?php echo $asignatura->getInscritos(); ?>' size='1' class="form-control" /></td>
											<td><input type='text' name='<?php echo $key; ?>[]' value='<?php echo $asignatura->getInicio(); ?>' class="form-control" required /></td>
											<td><input type='text' name='<?php echo $key; ?>[]' value='<?php echo $asignatura->getTermino(); ?>' class="form-control" required /></td>
											<td><input type='text' name='<?php echo $key; ?>[]' value='<?php echo ($asignatura->getDia() != null ? utf8_encode($asignatura->getDia()) : 'N/A'); ?>' class="form-control" required /></td>
											<td><input type='text' name='<?php echo $key; ?>[]' value='<?php echo ($asignatura->getModalidad() != 'ONLINE' ? $asignatura->getSala() : 'N/A'); ?>' class="form-control" /></td>
										</tr>
									<?php $con++;?>
									<?php endforeach;?>
									</tbody>
									<tfoot>
										<tr>
											<th>Número</th><th>Nombre</th><th>Código</th><th>Sección</th><th>Profesor</th><th>Modalidad</th><th>Capacidad</th><th>Cupos</th><th>Inscritos</th><th>Inicio</th><th>Término</th><th>Día</th><th>Sala</th>
										</tr>
									</tfoot>
								</table>
							</form>
							
							
							
							<?php
							var_dump($resultado_oferta);
							
							



							
							/**
							* FIN PROCESO OFERTA
							*/
						}
					}
                    
                } 
            }
                echo "<input type='hidden' name='import' value='true' />";
            ?>
            
    </div> <!-- /container -->
    
<?php
    if(isset($_POST['import'])){
        $alumnos_importar = array();
        $asignaturas_importar = array();
        $archivo = $_POST['archivo'];
        $alumnos_error = $_POST['conAlumnos'];
        unset($_POST['import']);
        unset($_POST['archivo']);
        unset($_POST['conAlumnos']);
        foreach($_POST as $key => $dato){
            if(strpos($key, "_") != false){
                if($dato[0] == "on"){
                    $asignaturas_importar[] = $key;
                }
            }else{
                if($dato == "on"){
                    $alumnos_importar[] = $key;
                } 
            }
        }
        //echo "<pre>"; var_dump($alumnos_importar); echo "</pre>";
        //echo "<pre>"; var_dump($asignaturas_importar); echo "</pre>";
        //echo "<pre>"; var_dump($_POST); echo "</pre>";
    }
    //echo "<pre>"; var_dump($resultado_acta); echo "</pre>";



?>  
<?php include("../templates/footer.php"); ?> 
