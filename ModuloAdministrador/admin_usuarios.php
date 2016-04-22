<?php include("header_admin.php"); ?>
<?php   
    
    $usuarios = $db->FAM_SELECT_USUARIOS();
    
?>

<script>
    $(document).ready(function() {
         $('#usuarios').DataTable( {
                "pagingType": "full_numbers",
                "order": [[ 2, "desc" ]],
                "language": {
                    "lengthMenu": "Mostrando _MENU_ datos por página.",
                    "zeroRecords": "No se encuentran registros.",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "paginate": {
                        "first":      "Primera",
                        "last":       "Última",
                        "next":       "Siguiente",
                        "previous":   "Anterior"
                    },
                    "infoEmpty": "No hay registros disponibles.",
                    "infoFiltered": "(Filtrado de _MAX_ registros totales.)"
                },
                "bFilter": false
        } );
    });
   
</script>


<nav class="navbar navbar-default">  
    <div class="loader"></div>
    <a class="navbar-brand logo" href="/../index.php">
        <img alt="Brand"  src="/../Imagenes/logo-U.jpg">
    </a>
    <h2>ADMINISTRADOR DE USUARIOS DEL SISTEMA</h2>    
</nav><br>

<!-- ZONA DE MENSAJES -->
<?php
    if(isset($_GET['msg'])){
        echo $_GET['msg'];
    }
?>
<!-- ZONA DE MENSAJES -->

<div class="panel panel-info container">
    <div class="panel-heading">REFERENCIAS ID PERFIL</div>
    <div class="panel-body">
        <ul>
            <li>Administrador: 1</li>
            <li>Alumno: 2</li>
            <li>Profesor: 3</li>
            <li>Director: 4</li>
        </ul>
    </div>
</div>

<div class="container">
    <input type="submit" data-toggle="modal" data-target="#usuario_nuevo" value="CREAR USUARIO" class="btn btn-warning form-control">
</div><br>

<!-- MODAL PARA NUEVO USUARIO -->
<div class="modal fade" id="usuario_nuevo" tabindex="-1" role="dialog" aria-labelledby="Usuario Nuevo">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4>CREAR USUARIO NUEVO</h4>
          </div>
          <div class="modal-body">
              <form action="../registrar_usuario.php" method="post" id="newuser">
                    <fieldset>
                    <legend>Datos usuario</legend>
                    <div class="form-group">
                        <label for="email">(Correo eléctronico sin el @mayor)</label>
                        <input class="form-control" type="text" placeholder="EJ: joshe.onate" name="email" id="email" required><br>
                        <label for="dominio">Dominio</label>
                        <select name="dominio" class="form-control" id="dominio">
                            <option value="">Seleccione dominio...</option>
                            <option value="@mayor.cl">@mayor.cl</option>
                            <option value="@umayor.cl">@umayor.cl</option>
                        </select><br>
                        <label for="rut">Rut (En el caso de ser Alumno)</label>
                        <input class="form-control" type="text" placeholder="Rut alumno.." name="rut" id="rut"><br>
                        <label for="tipo">Tipo Perfil</label>
                        <select name="tipo" class="form-control" id="tipo">
                            <option value="">Seleccione Perfil...</option>
                            <option value="1">Administrador</option>
                            <option value="2">Alumno</option>
                            <option value="3">Profesor</option>
                            <option value="4">Director</option>
                        </select><br>
                        <input type="hidden" name="admin_registro" value="0">
                    </div>
                    </fieldset>
              </form>
          </div>
          <div class="modal-footer">
              <button type="submit" onclick="$('#newuser').submit();" class="btn btn-success">Crear</button>
              <button class="btn btn-default" data-dismiss="modal">Cancelar</button>
          </div>
      </div>
  </div>   
</div>
<!-- FIN MODAL PARA NUEVO USUARIO -->


<form action="procesos_database/actualizar_usuarios.php" method="POST">
    
    <?php if($usuarios != ''): ?>
        <div class="container">
            <input type="submit" value="ACTUALIZAR USUARIOS" class="btn btn-success form-control">
        </div><br><br>
    <?php endif;?>
        
    <table id="usuarios" class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th><th>USUARIO</th><th>CORREO</th><th>RUT ALUMNO</th><th>PERFIL</th>
            </tr>
        </thead>
        <tbody>
            <?php if($usuarios != ''): ?>
                <?php foreach($usuarios as $usuario):?>
                    <tr>
                        <td><?php echo $usuario['ID_USUARIO'] ?></td>
                        <td><input type="text" name="<?php echo $usuario['ID_USUARIO'] ?>[]" class="form-control" value="<?php echo $usuario['USUARIO'] ?>"></td>
                        <td><input type="text" name="<?php echo $usuario['ID_USUARIO'] ?>[]" class="form-control" value="<?php echo $usuario['EMAIL'] ?>"></td>
                        <td><input type="text" name="<?php echo $usuario['ID_USUARIO'] ?>[]" class="form-control" value="<?php echo $usuario['RUT_ALUMNO'] ?>"></td>
                        <td><input type="text" name="<?php echo $usuario['ID_USUARIO'] ?>[]" class="form-control" value="<?php echo $usuario['ID_PERFIL'] ?>"></td>
                    </tr>
                <?php endforeach;?>
            <?php endif;?>
        </tbody>
        <tfoot>
            <tr>
                <th>ID</th><th>USUARIO</th><th>CORREO</th><th>RUT ALUMNO</th><th>PERFIL</th>
            </tr>
        </tfoot>
    </table>
</form>

    

<?php include("../templates/footer.php"); ?>