<?php include("templates/header.php"); ?>
<?php include("templates/navbar.php"); ?>
<?php

    foreach (glob("/ModuloImportador/clases/*.php") as $filename)
    {
        include $filename;
    }
    
    $alumnos = $db->select_alumnos();
    $asignaturas = $db->select_asignaturas();

?>

<div>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation" class="active"><a href="#alumno" aria-controls="alumno" role="tab" data-toggle="tab">Alumno</a></li>
      <li role="presentation"><a href="#asignatura" aria-controls="asignatura" role="tab" data-toggle="tab">Asignaturas</a></li>
      <li role="presentation"><a href="#reporte" aria-controls="reporte" role="tab" data-toggle="tab">Reporte</a></li>
      <li role="presentation"><a href="#administrador" aria-controls="administrador" role="tab" data-toggle="tab">Administrador</a></li>
      <li role="presentation"><a href="#importador" onclick="Javascript: window.location='ModuloImportador/index.php'" aria-controls="importador" role="tab" data-toggle="tab">Modulo Importador</a></li>
    </ul>

        <!-- Tab panes -->
    <div class="tab-content">
    
            <div role="tabpanel" class="tab-pane active content" id="alumno">
                <div class="col-md-12">
                        <table id="example_alumnos" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Rut</th><th>Matrícula</th><th>Nombre</th><th>Cod. Plan</th><th>Estado</th><th>Ver Alumno</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($alumnos as $alumno): ?>
                                    <tr>
                                        <td class="rut"><?php echo $alumno['RUT']; ?></td><td><?php echo $alumno['N_MATRICULA']; ?></td><td><?php echo utf8_decode($alumno['NOMBRES']); ?></td>
                                        <td><?php echo $alumno['CODIGO_PLAN']; ?></td>
                                        <td><?php echo $alumno['ESTADO_ESTUDIO']; ?></td><td><input type="button" class="btn btn-info" value="Ver" onclick="Javascript: selectAlumno('<?php echo $alumno['RUT']; ?>');" ></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Rut</th><th>Matrícula</th><th>Nombre</th><th>Cod. Plan</th><th>Estado</th><th>Ver Alumno</th>
                                </tr>
                            </tfoot>
                        </table>
                </div>
            </div>
        
            
            
        
        <div role="tabpanel" class="tab-pane content" id="asignatura">
            <div class="col-md-12">
                <table id="example_asignaturas" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Código</th><th>Nombre</th><th>Plan Estudio</th><th>Ver Asignatura</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($asignaturas as $asig): ?>
                            <tr>
                                <td><?php echo $asig['COD_ASIGNATURA']; ?></td><td><?php echo utf8_decode($asig['NOM_ASIGNATURA']); ?></td>
                                <td><?php echo $asig['PLANESTUDIO_COD_PLANESTUDIO']; ?></td>
                                <td><input type="button" class="btn btn-info" value="Ver" onclick="Javascript: selectAsignatura('<?php echo $asig['COD_ASIGNATURA']; ?>');" ></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Código</th><th>Nombre</th><th>Plan Estudio</th><th>Ver Asignatura</th>
                        </tr>
                    </tfoot>
                </table>
             </div>
        </div>
        
        <div role="tabpanel" class="tab-pane content" id="reporte">
            <div class="col-md-2 barra"></div>
                <div class="col-md-8 contenido">
            <h4>
                Homologaciones
            </h4>
            <hr>
            <ul class="list-group">
                <li class="list-group-item"><button class="btn btn-default">Ver Reporte</button>  -Registro de homlogaciones de asignaturas.</li>
                <li class="list-group-item"><button class="btn btn-default">Ver Reporte</button>  -Emisión de informes de asignaturas rendidos, incluye homologación.</li>
            </ul>
            <h4>
                Informe General
            </h4>
            <hr>
            <ul class="list-group">
                <li class="list-group-item"><button class="btn btn-default">Ver Reporte</button>  -Emisión de proyección de asignaturas a dictar.</li>
                <li class="list-group-item"><button class="btn btn-default">Ver Reporte</button>  -Tasa de aprobación y reprobación.</li>
                <li class="list-group-item"><button class="btn btn-default">Ver Reporte</button>  -Administración y centralización de práctica.</li>
                <li class="list-group-item"><button class="btn btn-default">Ver Reporte</button>  -Informe de no reprobación semestral.</li>
                <li class="list-group-item"><button class="btn btn-default">Ver Reporte</button>  -Informe de cumplimiento de Bachiller y licenciatura.</li>
                <li class="list-group-item"><button class="btn btn-default">Ver Reporte</button>  -Informe estadístico por sexo.</li>
            </ul>
             </div>
                <div class="col-md-2 barra"></div>
        </div>
        
        <div role="tabpanel" class="tab-pane content" id="administrador">
            <div class="col-md-2 barra"></div>
                <div class="col-md-8 contenido">
            <form action="#" method="post" name="loginAdmin" class="loginAdmin">
                <div class="form-group">
                    <label for="usuario">Usuario</label>
                    <input type="text" class="form-control" id="usuario" placeholder="Usuario">
                </div>
                <div class="form-group">
                    <label for="contraseña">Contraseña</label>
                    <input type="password" class="form-control" id="contraseña" placeholder="Contraseña">
                </div>
                <button type="submit" class="btn btn-success">Ingresar</button>
            </form>
        </div>
         </div>
                <div class="col-md-2 barra"></div>
                     
    </div>
        <!-- Tab panes end -->
  
</div>




  
<?php include("templates/footer.php"); ?> 
