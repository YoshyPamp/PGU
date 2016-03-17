<?php include("templates/header.php"); ?>
<?php include("templates/navbar.php"); ?>
<?php

	foreach (glob("/ModuloImportador/clases/*.php") as $filename)
    {
        include $filename;
    }
	
	
    if(isset($_GET['rut'])){
        $alumno = $db->select_alumno_rut($_GET['rut']);
        $nombreCompleto = explode(",",$alumno['NOMBRES']);
        $nombre = trim($nombreCompleto[1]);
        $apellidos = explode(" ",$nombreCompleto[0]);
        $apellidoPat = trim($apellidos[0]);
        $apellidoMat = trim($apellidos[1]);     
    }
    
	//var_dump($alumno);
	
	$asignaturas = $db->SELECT_ASIGNATURAS_BYPLAN($alumno['CODIGO_PLAN']);
	$historico = $db->SELECT_SECCION_NOTA_BYRUT($_GET['rut']);
	
	$duracion = $asignaturas['DURACION'];
	$ramos_FG = array();

	unset($asignaturas['DURACION']);
	if($duracion > 0){
		$width = floor(100 / $duracion);
	}else{
		echo "<div class='alert alert-danger' role='alert'>ERROR: NO EXISTE PLAN DE ESTUDIO: <b>".$alumno['CODIGO_PLAN']."</b> EN BASE DE DATOS.</div>";
	}

	//var_dump($historico);


	
	
?>

<div class="col-md-3"></div>
<div class="panel panel-default col-md-6">
    <div class="panel-heading" data-toggle="collapse" href="#collapseInformacion"  aria-controls="collapseInformacion">
        <h2 class="panel-title">Información Personal</h2>
    </div>
  <div class="panel-body" id='collapseInformacion' >
      <div class="form-group">
          <label>Nombre: </label>
          <input type="text" value="<?php echo $nombre; ?>" class="form-control" readonly> 
          <label>Apellido Paterno: </label>
          <input type="text" value="<?php echo $apellidoPat; ?>" class="form-control" readonly> 
          <label>Apellido Materno: </label>
          <input type="text" value="<?php echo $apellidoMat; ?>" class="form-control" readonly>
          <label>Rut: </label>
          <input type="text" value="<?php echo $alumno['RUT']; ?>" class="form-control" readonly>
          <label>Matrícula: </label>
          <input type="text" value="<?php echo $alumno['N_MATRICULA']; ?>" class="form-control" readonly>
          
          <br>
          <div class="alert alert-warning" role="alert">Bachillerato</div>
          <div class="alert alert-warning" role="alert">Licenciatura</div>
          <div class="alert alert-success" role="alert">Práctica 2</div>
          <div class="alert alert-success" role="alert">Práctica 2</div>
      </div>
  </div>
</div>
<div class="col-md-3"></div>




<div class="panel panel-default col-md-12">
    <div class="panel-heading" data-toggle="collapse" href="#collapseHorario" aria-controls="collapseHorario">
        <h2 class="panel-title">Horario</h2>
    </div>
  <div class="panel-body collapse" id='collapseHorario'>
    <table class="table table-condensed" >
        <th>Modulo</th><th>Lunes</th><th>Martes</th><th>Miercoles</th><th>Jueves</th><th>Viernes</th><th>Sábado</th>
        <tr>
            <td>01</td><td><p>Biología ICV4560 MALI-207</p></td><td><p>Biología ICV4560 MALI-207</p></td><td><p>Biología ICV4560 MALI-207</p></td>
            <td><p>Biología ICV4560 MALI-207</p></td><td><p>Biología ICV4560 MALI-207</p></td><td><p>Biología ICV4560 MALI-207</p></td>
        </tr>
        <tr>
            <td>02</td><td><p>Biología ICV4560 MALI-207</p></td><td><p>Biología ICV4560 MALI-207</p></td><td><p>Biología ICV4560 MALI-207</p></td>
            <td><p>Biología ICV4560 MALI-207</p></td><td><p>Biología ICV4560 MALI-207</p></td><td><p>Biología ICV4560 MALI-207</p></td>
        </tr>
        <tr>
            <td>03</td><td><p>Biología ICV4560 MALI-207</p></td><td><p>Biología ICV4560 MALI-207</p></td><td><p>Biología ICV4560 MALI-207</p></td>
            <td><p>Biología ICV4560 MALI-207</p></td><td><p>Biología ICV4560 MALI-207</p></td><td><p>Biología ICV4560 MALI-207</p></td>
        </tr>
        <tr>
            <td>04</td><td><p>Biología ICV4560 MALI-207</p></td><td><p>Biología ICV4560 MALI-207</p></td><td><p>Biología ICV4560 MALI-207</p></td>
            <td><p>Biología ICV4560 MALI-207</p></td><td><p>Biología ICV4560 MALI-207</p></td><td><p>Biología ICV4560 MALI-207</p></td>
        </tr>
        
    </table>
  </div>
</div>

<div class="panel panel-default col-md-12">
    <div class="panel-heading" data-toggle="collapse" href="#collapsePlan" aria-controls="collapsePlan">
        <h2 class="panel-title">Plan de Estudio</h2>
    </div>
  <div class="panel-body collapse" id='collapsePlan'>
       <?php foreach($asignaturas as $nivel => $ramos): ?>
			<table class="table table-condensed" style=" float: left; width: <?php echo $width; ?>%;">
				<thead>
					<tr>
						<th><?php echo $nivel; ?></th>
					</tr>
				</thead>
				
				<tbody>
					<?php foreach($ramos as $ramo): ?>
					<?php 	if(substr($ramo[1],0,2) == 'FG'):?>
					<?php 		$ramos_FG[] = $ramo ?>
					<?php 	else:?>
						<tr class="info">
							<td style=" font-family: 'Courier';">
								<?php echo $ramo[0]; ?><br>
								<em><?php echo $ramo[1]; ?></em>
							</td>
						</tr>
					<?php 	endif;?>
					<?php endforeach;?>
				</tbody>
			</table>
	   <?php endforeach;?>
	   <?php if(count($ramos_FG) > 0):?>
		<div>
			<table class="table table-responsive">
				<thead>
					<tr><th>Electivos de Formación General</th></tr>
				</thead>
				<tbody>
					<tr class="info">
					<?php foreach($ramos_FG as $ramo): ?>
						<td style=" font-family: 'Courier'; float: left; border: solid light-grey 1px; ">
							<?php echo $ramo[0]; ?><br>
							<em><?php echo $ramo[1]; ?></em>
						</td>
					<?php endforeach;?>
					</tr>
				</tbody>
			</table>
		</div>
		<?php endif;?>
  </div>
</div>

<div class="panel panel-default col-md-12">
    <div class="panel-heading" data-toggle="collapse" href="#collapseHistorico" aria-controls="collapseHistorico">
        <h2 class="panel-title">Histórico</h2>
    </div>
  <div class="panel-body collapse" id='collapseHistorico'>
    <table class="table table-striped table-bordered" id="historico_alumno">
		<thead>
			<tr>
				<th>Código</th><th>Nombre</th><th>Semestre</th><th>Año</th><th>Nivel</th><th>Nota Final</th><th>Estado</th><th>VER</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>Código</th><th>Nombre</th><th>Semestre</th><th>Año</th><th>Nivel</th><th>Nota Final</th><th>Estado</th><th>VER</th>
				
			</tr>
		</tfoot>
        <tbody>
			<tr>
				<?php foreach($historico as $seccion):?>
					<td><?php echo $seccion['COD_SECCION']; ?></td>
					<td><?php echo $seccion['NOM_ASIGNATURA']; ?></td>
					<td><?php echo $seccion['SEMESTRE']; ?></td>
					<td><?php echo $seccion['ANO']; ?></td>
					<td><?php echo $seccion['NIVEL'];?></td>
					<td><?php echo round($seccion['NOTA'],1); ?></td>
					<td><?php echo $seccion['ESTADO']; ?></td>
					<td><input type='button' class='btn btn-info' value='IR'></td>
				<?php endforeach;?>
			</tr>
		</tbody>
    </table>
  </div>
</div>



<?php include("templates/footer.php"); ?> 
