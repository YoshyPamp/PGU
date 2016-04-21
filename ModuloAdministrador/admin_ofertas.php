<?php include("header_admin.php"); ?>

<!-- ZONA DE FUNCIONES PHP -->
<?php 
    
    if(isset($_GET['ano']) && isset($_GET['semestre'])){
        $secciones = $db->FAM_SELECT_OFERTA($_GET['ano'], $_GET['semestre']);
        if($secciones != ''){
            
        }else{
            echo "<script>alert('No existe oferta para ese período.')</script>";
        }
    }  
?>
<!-- FIN ZONA DE FUNCIONES PHP -->

<script>
    $(document).ready(function() {
         $('#oferta_table').DataTable( {
                "pagingType": "full_numbers",
                "order": [[ 2, "desc" ]],
                "language": {
                "lengthMenu": "Mostrando _MENU_ datos por página.",
                "zeroRecords": "No se encuentran registros.",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "search": "Buscar:",
                "paginate": {
                    "first":      "Primera",
                    "last":       "Última",
                    "next":       "Siguiente",
                    "previous":   "Anterior"
                },
                "infoEmpty": "No hay registros disponibles.",
                "infoFiltered": "(Filtrado de _MAX_ registros totales.)"
                    }
        } );
    });
</script>


<!-- ZONA DE CABECERA -->
    <nav class="navbar navbar-default"> 
        <div class="loader"></div>
        <a class="navbar-brand logo" href="/../index.php">
            <img alt="Brand"  src="/../Imagenes/logo-U.jpg">
        </a>
        <h2>ADMINISTRADOR DE OFERTAS</h2>    
    </nav>
<!-- FIN ZONA DE CABECERA -->


<!-- ZONA DE MENSAJES -->
<?php
    if(isset($_GET['msg'])){
        echo $_GET['msg'];
    }
?>
<!-- FIN ZONA DE MENSAJES -->


<!-- ZONA DE BUSQUEDA Y RESULTADO -->
<div class="container">
    <form action="" method="GET" name="forma_oferta" id="forma_oferta">
        <legend>Buscar oferta a modificar.</legend>
        <input type="number" class="form-control" name="ano" id="ano" placeholder="Año Oferta" required><br>
        <input type="number" class="form-control" name="semestre" id="semestre" placeholder="Semestre 1,2" required><br>
        <input type="submit" class="btn btn-warning" value="BUSCAR" >
    </form>
</div><br><br>

<div class="container">
    
    <label for="ano_oferta">Año:</label>
    <input type="number" class="form-control" name="ano_oferta" id="ano_oferta" value="<?php echo (isset($_GET['ano']) ? $_GET['ano'] : ''); ?>" placeholder="Año Oferta"><br>
    <label for="semestre_oferta">Semestre:</label>
    <input type="number" class="form-control" name="semestre_oferta" id="semestre_oferta" value="<?php echo (isset($_GET['semestre']) ? $_GET['semestre'] : ''); ?>" placeholder="Semestre 1,2"><br>
    
    <?php if(isset($_GET['ano'])):?>
    <input type="submit" value="ACTUALIZAR OFERTA" class="form-control btn btn-success"><br><br><br>
    <?php endif;?>
</div>
<div class="container-fluid">
    
            <?php 
            if(isset($secciones) && $secciones != ''): 
                ?>
    <table id="oferta_table" class="table table-striped table-bordered" width="100%" >
        <thead>
            <tr>
                <th>ID</th><th>Código Sección</th><th>Profesor</th><th>Inscritos</th><th>Cupos</th><th>Capacidad</th><th>Día</th><th>Inicio</th><th>Término</th><th>Modalidad</th>
            </tr>
        </thead>
        <tbody>
            <?php
                 foreach($secciones as $seccion):
                    ?>
            <tr>
                <td><?php echo $seccion['ID_SECCION'] ?></td>
                <td><input type="text" class="form-control" value="<?php echo $seccion['COD_SECCION'] ?>" name="COD_SECCION" /></td>
                <td><input type="text" class="form-control" value="<?php echo $seccion['PROFESOR_NOMBRE'] ?>" name="PROFESOR_NOMBRE" /></td>
                <td><input type="text" class="form-control" value="<?php echo $seccion['INSCRITOS'] ?>" name="INSCRITOS" /></td>
                <td><input type="text" class="form-control" value="<?php echo $seccion['CUPOS'] ?>" name="CUPOS" /></td>
                <td><input type="text" class="form-control" value="<?php echo $seccion['CAPACIDAD'] ?>" name="CAPACIDAD" /></td>
                <td><input type="text" class="form-control" value="<?php echo utf8_encode($seccion['DIA']) ?>" name="DIA" /></td>
                <td><input type="text" class="form-control" value="<?php echo $seccion['INICIO'] ?>" name="INICIO" /></td>
                <td><input type="text" class="form-control" value="<?php echo $seccion['TERMINO'] ?>" name="TERMINO" /></td>
                <td><input type="text" class="form-control" value="<?php echo $seccion['MODALIDAD'] ?>" name="MODALIDAD" /></td>
            </tr>
            
                    <?php
                 endforeach; 
                 ?>
            </tbody>
        <tfoot>
            <tr>
                <th>ID</th><th>Código Sección</th><th>Profesor</th><th>Inscritos</th><th>Cupos</th><th>Capacidad</th><th>Día</th><th>Inicio</th><th>Término</th><th>Modalidad</th>
            </tr>
        </tfoot>
    </table>
            <?php
            endif; ?>   
    
</div>
<!-- FIN ZONA DE BUSQUEDA Y RESULTADO -->

<?php include("../templates/footer.php"); ?>