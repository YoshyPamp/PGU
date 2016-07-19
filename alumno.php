<?php include("templates/header.php"); ?>
<?php include("templates/navbar.php"); ?>
<?php

	foreach (glob("/ModuloImportador/clases/*.php") as $filename)
    {
        include $filename;
    }
    
    if($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 2 && $_SESSION['perfil'] != 4){
        header("location: index.php");
    }
    
	
    if(isset($_GET['rut'])){
        $alumno = $db->FAM_SELECT_ALUMNO_RUT($_GET['rut']);
        $nombreCompleto = explode(",",$alumno['NOMBRES']);
        $nombre = trim($nombreCompleto[1]);
        $apellidos = explode(" ",$nombreCompleto[0]);
        $apellidoPat = trim($apellidos[0]);
        $apellidoMat = trim($apellidos[1]); 
        $plus = '';
        
        $resultado = $db->FAM_VERIFICAR_NIVEL_MINIMO_ASIGNATURAS($alumno['CODIGO_PLAN'], $_GET['rut']);
        $nivel_minimo = $resultado['NIVEL_MINIMO'];
    }
    
	if($db->VERIFICAR_PLAN_EXISTENTE($alumno['CODIGO_PLAN'])){
		
		$asignaturas = $db->SELECT_ASIGNATURAS_BYPLAN($alumno['CODIGO_PLAN']);
		$historico = $db->SELECT_SECCION_NOTA_BYRUT($_GET['rut']);
                $horario = $db->FAM_SELECT_SECCIONES_BY_RUT_OFERTA($_GET['rut'], date('Y'), $sem);
                
		$duracion = $asignaturas['DURACION'];
		$ramos_FG = array();
		
		unset($asignaturas['DURACION']);
		$existe = true;
		$width = floor(100 / $duracion);
	}else{
		$existe = false;
		echo "<div class='alert alert-danger' role='alert'>ERROR: NO EXISTE PLAN DE ESTUDIO: <b>".$alumno['CODIGO_PLAN']."</b> EN BASE DE DATOS.</div>";
	}
        
        //xdebug_dump_superglobals();
        //var_dump($horario);
      
	
?>

<div class="col-md-3">
    <a href="index.php?activo=alumno"><img src="Imagenes/back.png" width="30px" heigth="30px"></a>
</div>
<div class="panel panel-default col-md-6">
    <div class="panel-heading">
        <h2 class="panel-title">Información Personal</h2>
    </div>
  <div class="panel-body">
      <div class="form-group">
          <label>Nombre: </label>
          <input type="text" value="<?php echo $nombre; ?>" class="form-control" readonly> 
          <label>Apellido Paterno: </label>
          <input type="text" value="<?php echo $apellidoPat; ?>" class="form-control" readonly> 
          <label>Apellido Materno: </label>
          <input type="text" value="<?php echo $apellidoMat; ?>" class="form-control" readonly>
          <label>Correo: </label>
          <input type="text" value="<?php echo $alumno['CORREO']; ?>" class="form-control" readonly>
          <label>Rut: </label>
          <input type="text" value="<?php echo $alumno['RUT']; ?>" class="form-control" readonly>
          <label>Matrícula: </label>
          <input type="text" value="<?php echo $alumno['N_MATRICULA']; ?>" class="form-control" readonly>
          <label>Plan de Estudio: </label>
          <input type="text" value="<?php echo $alumno['CODIGO_PLAN']; ?>" class="form-control" readonly>
          <label>Año de ingreso: </label>
          <input type="text" value="<?php echo $alumno['ANO_INGRESO']; ?>" class="form-control" readonly><br>
          <input type="button" value="Cambiar Plan Actual" class="btn btn-warning" data-toggle="modal" data-target="#cambio_plan" ><br><br>
          <input type="button" value="Ver Planes Anteriores" class="btn btn-success" onclick="window.location='alumno_planes.php?rut=<?php echo $alumno['RUT']; ?>'"><br>
          
          <br>
            <?php if($nivel_minimo > 4): ?>
                <div class="alert alert-success" role="alert"><b>Cumple</b> con requisitos para Bachillerato.</div>
            <?php else: ?>
                <div class="alert alert-warning" role="alert"><b>No cumple</b> con requisitos para Bachillerato.</div>
            <?php endif;?>
                
            <?php if($nivel_minimo > 8): ?>
                <div class="alert alert-success" role="alert"><b>Cumple</b> con requisitos para Licenciatura.</div>
            <?php else: ?>
                <div class="alert alert-warning" role="alert"><b>No cumple</b> con requisitos para Licenciatura.</div>
            <?php endif;?>
      </div>
  </div>
</div>
<div class="col-md-3"></div>

<!-- MODAL PARA CAMBIAR PLAN -->
    <div id="cambio_plan" class="modal fade" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>CAMBIO DE PLAN</h4>
                </div>
                <div class="modal-body">
                    <legend>Ingrese Datos</legend>
                    <label>Estado Plan Actual:</label>
                    <select id="estado_actual" class="form-control">
                        <option value="">Seleccione opción...</option>
                        <option value="INCOMPLETO">INCOMPLETO</option>
                        <option value="COMPLETO">COMPLETO</option>
                        <option value="SUSPENDIDO">SUSPENDIDO</option>
                    </select>
                    <br>
                    <?php $planes = $db->FAM_SELECT_PLANES_ESCUELA('ICC-01');?>
                    <label>Código Plan Nuevo:</label>
                    <select class="form-control" id="plan_nuevo">
                        <option value="">Seleccione plan...</option>
                        <?php foreach($planes as $plan): ?>
                            <option 
                                value="<?php echo $plan['COD_PLANESTUDIO']; ?>">
                                <?php echo utf8_encode($plan['NOM_PLANESTUDIO'])." | ".$plan['COD_PLANESTUDIO']; ?>
                            </option>
                        <?php endforeach;?>
                    </select>
                    <br>
                    <label>Año de ingreso nuevo</label>
                    <input type="text" class="form-control" value="<?php echo date('Y'); ?>" id="ano_nuevo">
                    <input type="hidden" value="<?php echo $alumno['ANO_INGRESO']; ?>" id="ano_ingreso_actual">
                    <input type="hidden" value="<?php echo $alumno['RUT']; ?>" id="rut_modal">
                    <input type="hidden" value="<?php echo $alumno['CODIGO_PLAN']; ?>" id="cod_plan_antiguo">
                    <br><br>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" onclick="ajax_cambiar_plan();">CAMBIAR PLAN</button>
                    <button class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- FINAL MODAL PARA CAMBIAR PLAN -->


<div class="panel panel-default col-md-12">
    <div class="panel-heading" data-toggle="collapse" href="#collapseHorario" aria-controls="collapseHorario">
        <h2 class="panel-title">Horario</h2>
    </div>
  <div class="panel-body collapse" id='collapseHorario'>
    <button class="btn btn-success btn-sm" onclick="$('#table-horario').tableExport({type:'excel'});">Exportar a XLS</button><br><br>
    <table class="table table-bordered table-responsive" id="table-horario" >
        <thead>
            <tr>
                <th>Modulo</th>
                <th><?php echo $dias_semana[0]; ?></th>
                <th><?php echo $dias_semana[1]; ?></th>
                <th><?php echo $dias_semana[2]; ?></th>
                <th><?php echo $dias_semana[3]; ?></th>
                <th><?php echo $dias_semana[4]; ?></th>
                <th><?php echo $dias_semana[5]; ?></th>
            </tr>
        </thead>
        <tbody>
            
            <?php foreach($modulos as $mod => $hor): ?>
                <tr>
                    <td class="col-md-1"><?php echo $hor[0].' - '.$hor[1]; ?></td>
                <?php foreach($dias_semana as $dia): ?>
                    <?php $sec = buscaSec($dia, $hor, $horario);?>
                    <?php if($sec != null):?>
                        <td class="info col-md-1" style="width: 10%;"><?php echo utf8_encode($sec['NOM_ASIGNATURA'])."<br>".$sec['COD_SECCION']."<br>".utf8_encode($sec['PROFESOR_NOMBRE'])."<br>".$sec['MODALIDAD']." (<b>".$sec['COD_SALA']."</b>)"; ?></td>
                    <?php else:?>
                        <td class="col-md-2"></td>
                    <?php endif; ?>
                <?php endforeach;?>
                </tr>
            <?php endforeach;?>
            
        </tbody>
    </table>
  </div>
</div>

<div class="panel panel-default col-md-12">
    <div class="panel-heading" data-toggle="collapse" href="#collapsePlan" aria-controls="collapsePlan">
        <h2 class="panel-title">Plan de Estudio</h2>
    </div>
  <div class="panel-body collapse" id='collapsePlan'>
	   <?php if($existe): ?>
       <?php foreach($asignaturas as $nivel => $ramos): ?>
                <table class="table table-condensed table-bordered" id="table-plan" style=" float: left; width: <?php echo $width; ?>%;">
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

                            <?php $estado = $db->FAM_SELECT_ESTADO_ASIGNATURA_BY_RUT($alumno['RUT'],$ramo[1].'%');?>

                            <?php if($estado == 'info'): ?>
                            <?php $estado = $db->FAM_SELECT_ESTADO_ASIGNATURA_BY_RUT_PLUS($alumno['RUT'],$ramo[1]);?>
                            <?php 
                                if($estado != 'info'){
                                    $plus = ' PLUS';
                                }else{
                                    $plus = '';
                                }
                            ?>
                            <?php endif;?>

                                    <tr class="<?php echo $estado; ?>">
                                            <td style=" font-family: 'Courier';">
                                                    <?php echo utf8_encode($ramo[0]); ?><br>
                                                    <em><?php echo $ramo[1]; ?><b style='color: blue;'><?php echo $plus; ?></b></em>
                                            </td>
                                    </tr>
                            <?php 	endif;?>
                            <?php endforeach;?>
                    </tbody>
                </table>
	   <?php endforeach;?>
	   <?php if(count($ramos_FG) > 0):?>
		<div>
                    <legend>Electivos de Formación General <em style="color: #01717c; cursor: pointer; " onclick="escondeElectivos();">(Presione para expandir o contraer)</em></legend>
                        <table class="table table-responsive table-bordered" id="electivos" style="display: none;">
				<tbody>
					<tr class="info">
					<?php foreach($ramos_FG as $ramo): ?>
						<td style=" font-family: 'Courier'; float: left; border: solid light-grey 1px; ">
							<?php echo utf8_encode($ramo[0]); ?><br>
							<em><?php echo $ramo[1]; ?></em>
						</td>
					<?php endforeach;?>
					</tr>
				</tbody>
			</table>
		</div>
		<?php endif;?>
		<?php endif;?>
  </div>
</div>

<div class="panel panel-default col-md-12">
    <div class="panel-heading" data-toggle="collapse" href="#collapseHistorico" aria-controls="collapseHistorico">
        <h2 class="panel-title">Histórico</h2>
    </div>
  <div class="panel-body collapse" id='collapseHistorico'>
    <button class="btn btn-success btn-sm" onclick="$('#historico_alumno').tableExport({type:'excel'});">Exportar a XLS</button><br>
    <table class="table table-striped table-bordered" width="100%" id="historico_alumno">
        <thead>
            <tr>
                <th>Código</th><th>Nombre</th><th>Semestre</th><th>Año</th><th>Nivel</th><th>Nota Final</th><th>Estado</th><th>VER</th>
            </tr>
        </thead>
        <tbody>

            <?php if($existe):?>
            <?php foreach($historico as $seccion):?>
                <tr>
                    <td><?php echo $seccion['COD_SECCION']; ?></td>
                    <td><?php echo utf8_encode($seccion['NOM_ASIGNATURA']); ?></td>
                    <td><?php echo $seccion['SEMESTRE']; ?></td>
                    <td><?php echo $seccion['ANO']; ?></td>
                    <td><?php echo $seccion['NIVEL'];?></td>
                    <td><?php echo round($seccion['NOTA'],1); ?></td>
                    <td><?php echo $seccion['ESTADO']; ?></td>
                    <td>
                        <button 
                            type='button' 
                            class='btn btn-info' 
                            onClick="MyWindow=window.open('asignatura_historica.php?id_sec=<?php echo $seccion['ID_SECCION'] ?>&rut=<?php echo $alumno['RUT']; ?>&ramo=<?php echo utf8_encode($seccion['NOM_ASIGNATURA']); ?>&ano_semestre=<?php echo $seccion['ANO'].'-'.$seccion['SEMESTRE']; ?>','MyWindow',width=600,height=300);" >
                            IR
                        </button>
                    </td>
                </tr>
            <?php endforeach;?>
            <?php endif;?>
        </tbody>
        <tfoot>
            <tr>
                <th>Código</th><th>Nombre</th><th>Semestre</th><th>Año</th><th>Nivel</th><th>Nota Final</th><th>Estado</th><th>VER</th>

            </tr>
        </tfoot>
    </table>
  </div>
</div>


<?php include("templates/footer.php"); ?> 
