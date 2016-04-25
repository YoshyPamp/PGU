<?php include("templates/header.php"); ?>
<?php include("templates/navbar.php"); ?>
<?php

    foreach (glob("/ModuloImportador/clases/*.php") as $filename)
    {
        include $filename;
    }
    
    //var_dump($_SESSION);
   
    /*
     *  PERMISOS
     *  NOMBRE - ID_PERMISO
     *  
     *  ADMINISTRADOR - 1
     *  ALUMNO - 2
     *  PROFESOR - 3
     *  DIRECTOR - 4
     * 
     */
?>

<div class="container alert alert-danger" id="error" style="display: none;"></div>
<div class="container alert alert-success" id="success" style="display: none;"></div>

<div>

    <!-- Nav Con permisos por cada perfil -->
    <ul class="nav nav-tabs" role="tablist">
        
        <li role="presentation" class="active"><a href="#inicio" aria-control="inicio" role="tab" data-toggle="tab">INICIO</a></li>
        
      <?php if($_SESSION['perfil'] == 1 || $_SESSION['perfil'] == 2 || $_SESSION['perfil'] == 4): ?>
        <li role="presentation"><a href="#alumno" aria-controls="alumno" role="tab" data-toggle="tab">Alumno</a></li>
      <?php endif; ?>
        
      <?php if($_SESSION['perfil'] == 1 || $_SESSION['perfil'] == 4 || $_SESSION['perfil'] == 3): ?>
        <li role="presentation"><a href="#asignatura" aria-controls="asignatura" role="tab" data-toggle="tab">Asignaturas</a></li>
      <?php endif;?>
        
      <?php if($_SESSION['perfil'] == 1 || $_SESSION['perfil'] == 4): ?>
        <li role="presentation"><a href="#reporte" aria-controls="reporte" role="tab" data-toggle="tab">Reportes</a></li>
      <?php endif;?>
        
      <?php if($_SESSION['perfil'] == 1): ?>
        <li role="presentation"><a href="#administrador" aria-controls="administrador" role="tab" data-toggle="tab">Administrador</a></li>
      <?php endif;?>
        
      <?php if($_SESSION['perfil'] == 1 || $_SESSION['perfil'] == 4): ?>
        <li role="presentation"><a href="#importador" onclick="Javascript: window.location='ModuloImportador/index.php'" aria-controls="importador" role="tab" data-toggle="tab">Importador</a></li>
      <?php endif;?>
        
        <li ><a href="#" data-toggle="modal" data-target="#myModal"><b><span class="glyphicon glyphicon-off"></span> Cerrar Sesión</b></a></li>
      <li ><a href="#" data-toggle="modal" data-target="#cambiar_clave"><b><span class="glyphicon glyphicon-wrench"></span> Cambiar Contraseña</b></a></li>
    </ul>
    
    
    
    <!-- MODAL PARA CAMBIAR CLAVE -->
    <div id="cambiar_clave" class="modal fade" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>CAMBIAR CONTRASEÑA DE USUARIO</h4>
                </div>
                <div class="modal-body">
                    <legend>Ingrese Datos</legend>
                    <label for="pass1">Contraseña Anterior</label>
                    <input type="password" name="pass1" id="pass1" class="form-control"><br>
                    <label for="pass2" >Contraseña Nueva</label>
                    <input type="password" name="pass2" id="pass2" class="form-control"><br>
                    <label for="pass3">Contraseña Nueva Confirmación</label>
                    <input type="password" name="pass3" id="pass3" class="form-control"><br><br>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" onclick="ajax_cambiar_pass();">Cambiar Contraseña</button>
                    <button class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- FINAL MODAL PARA CAMBIAR CLAVE -->
    
    
    <!-- MODAL PARA CERRAR SESIÓN -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-sm">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-body">
              <p>¿Seguro que quiere cerrar sesión?</p>
            </div>
              <form action="Config/Login/logout.php" action="post">
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Cerrar Sesión</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
          </div>
        </div>
      </div>
    <!-- FINAL MODAL PARA CERRAR SESIÓN -->

    
    <div class="tab-content">
        
        <?php //INICIO ?>
        <div role="tabpanel" class="tab-pane content active" id="inicio">
            <div class="jumbotron text-center">
                <h2>
                    Bienvenido al Sistema de Gestión Universitario <br> <label class="text-danger">Universidad</label> <label class="text-success">Mayor</label>
                </h2>
                <div class="well">
                    <p>Usuario: <b><em><?php echo $_SESSION['usuario']; ?></em></b></p>
                    <p>Perfil: <b><em><?php echo $_SESSION['nom_perfil']; ?></em></b></p>
                </div>
            </div>
        </div>
        
            <?php //ALUMNO ?>
    <?php if($_SESSION['perfil'] == 1 || $_SESSION['perfil'] == 2 || $_SESSION['perfil'] == 4): ?>
        
    <?php if($_SESSION['perfil'] != 2):?>
    <?php   $alumnos = $db->FAM_SELECT_ALUMNOS(); ?>
    <?php else: ?>
    <?php   $alumnos[] = $db->FAM_SELECT_ALUMNO_RUT($_SESSION['rut_alumno']); ?>
    <?php endif;?>
    
            <div role="tabpanel" class="tab-pane content" id="alumno">
                <div class="col-md-12">
                        <table id="example_alumnos" width="100%" class="table table-striped table-bordered">
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
        <?php endif; ?>
        
            
            
        <?php //ASIGNATURA ?>
        <?php if($_SESSION['perfil'] == 1 || $_SESSION['perfil'] == 4 || $_SESSION['perfil'] == 3): ?>
        
        <?php   $asignaturas = $db->FAM_SELECT_ASIGNATURAS(); ?>
        
        <div role="tabpanel" class="tab-pane content" id="asignatura">
            <div class="col-md-12">
                <table id="example_asignaturas" class="table table-striped table-bordered" width="100%">
                    <thead>
                        <tr>
                            <th>Código</th><th>Nombre</th><th>Plan Estudio</th><th>Ver Asignatura</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($asignaturas as $asig): ?>
                            <tr>
                                <td><?php echo $asig['COD_ASIGNATURA']; ?></td><td><?php echo $asig['NOM_ASIGNATURA']; ?></td>
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
        <?php endif; ?>
        
        
        <?php //REPORTES ?>
        <?php if($_SESSION['perfil'] == 1 || $_SESSION['perfil'] == 4): ?>
        <div role="tabpanel" class="tab-pane content" id="reporte">
            <div class="col-md-2 barra"></div>
                <div class="col-md-8 contenido">
            <h4>
                Homologaciones
            </h4>
            <hr>
            <ul class="list-group">
                <li class="list-group-item"><a class="btn btn-default" href='ModuloReportes/HomologacionAsignaturas.php'>Ver Reporte</a>  -Registro de homlogaciones de asignaturas.</li>
                <li class="list-group-item"><a class="btn btn-default" href='ModuloReportes/ReporteAsignaturasRendidas.php'>Ver Reporte</a>  -Emisión de informes de asignaturas rendidos, incluye homologación.</li>
            </ul>
            <h4>
                Informe General
            </h4>
            <hr>
            <ul class="list-group">
                <li class="list-group-item"><a class="btn btn-default" href='ModuloReportes/ProyeccionAsignaturas.php'>Ver Reporte</a>  -Emisión de proyección de asignaturas a dictar.</li>
                <li class="list-group-item"><a class="btn btn-default" href='#'>Ver Reporte</a>  -Tasa de aprobación y reprobación.</li>
                <li class="list-group-item"><a class="btn btn-default" href='ModuloReportes/AdminyCentPractica.php'>Ver Reporte</a>  -Administración y centralización de práctica.</li>
                <li class="list-group-item"><a class="btn btn-default" href='ModuloReportes/AprobacionSemestral.php'>Ver Reporte</a>  -Informe de no reprobación semestral.</li>
                <li class="list-group-item"><a class="btn btn-default" href='#'>Ver Reporte</a>  -Informe de cumplimiento de Bachiller y licenciatura.</li>
                <li class="list-group-item"><a class="btn btn-default" href='#'>Ver Reporte</a>  -Informe estadístico por sexo.</li>
            </ul>
             </div>
                <div class="col-md-2 barra"></div>
        </div>
        <?php endif; ?>
        
        <?php //ADMINISTRADOR ?>
        <?php if($_SESSION['perfil'] == 1): ?>
        <div role="tabpanel" class="tab-pane content" id="administrador">
            <div class="col-md-2 barra"></div>
                <div class="col-md-8 contenido">
                    <legend>Opciones de Administrador</legend>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <h3>
                                <a class="btn btn-info form-control" href="ModuloAdministrador/admin_usuarios.php">Usuarios del Sistema</a>
                            </h3>
                        </li>
                        <li class="list-group-item">
                            <h3>
                                <a class="btn btn-info form-control" href="ModuloAdministrador/admin_planesdeestudio.php">Planes de Estudio</a>
                            </h3>
                        </li>
                        <li class="list-group-item">
                            <h3>
                                <a class="btn btn-info form-control" href="ModuloAdministrador/admin_ofertas.php">Ofertas</a>
                            </h3>
                        </li>
                        <li class="list-group-item">
                            <h3>
                                <a class="btn btn-info form-control" href="ModuloAdministrador/admin_secciones.php">Secciones</a>
                            </h3>
                        </li>
                        <li class="list-group-item">
                            <h3>
                                <a class="btn btn-info form-control" href="ModuloAdministrador/admin_alumnos.php">Alumnos</a>
                            </h3>
                        </li>
                    </ul>
                </div>
         </div>
        <?php endif; ?>
                <div class="col-md-2 barra"></div>
                     
    </div>
        <!-- Tab panes end -->
  
</div>

<?php if(isset($_SESSION['user_id'])): ?>
    <input type="hidden" id="user_id" value="<?php echo $_SESSION['user_id']; ?>">
<?php endif;?>

  
<?php include("templates/footer.php"); ?> 
