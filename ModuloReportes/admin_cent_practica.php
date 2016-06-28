<?php include("header_reportes.php"); ?>
<?php 
    if(isset($_GET['rut'])){
        $alumno = $db->FAM_SELECT_ALUMNO_RUT($_GET['rut']);
        $resultado = $db->FAM_VERIFICAR_NIVEL_MINIMO_ASIGNATURAS($alumno['CODIGO_PLAN'], $_GET['rut']);
    }
    
    if(isset($_GET['msg'])){
        echo $_GET['msg'];
    }
?>


<div class='container'>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h2 class="panel-title">Administración y Centralización de Práctica</h2>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <form method='GET' action='admin_cent_practica.php'>
                    <label>RUT: </label><input type="text" placeholder="Ej: 165557778" name='rut' class="form-control"><br>
                    <input type="submit" value="BUSCAR" class='btn btn-warning'/><br><br>
                </form>
                <label>Rut: </label>
                <input type="text" class="form-control" value='<?php echo (isset($_GET['rut']) ? $alumno['RUT']: ''); ?>' readonly><br>
                <label>Nombre: </label>
                <input type="text" class="form-control" value='<?php echo (isset($_GET['rut']) ? $alumno['NOMBRES']: ''); ?>' readonly><br>
                <label>Plan Estudio: </label>
                <input type="text" class="form-control" value='<?php echo (isset($_GET['rut']) ? $alumno['CODIGO_PLAN']: ''); ?>' readonly><br>
                <label>Nivel de Avance: </label>
                <input type="text" class="form-control" value='<?php echo (isset($_GET['rut']) ? 'NIVEL '.$resultado['NIVEL_MINIMO']: ''); ?>' readonly>
            </div>
            <hr>
            <div class='well'>
                <div class="form-group">
                    <form method='POST' action='procesos_bd/guarda_practica.php'>
                        <h2 class="panel-title"><b>Estado de Práctica</b></h2><br>
                        <input type="radio" name="estado[]" value="EN CURSO" <?php echo (isset($alumno) && $alumno['ESTADO_PRACTICA'] == 'EN CURSO') ? 'checked': ''  ?>><b> En curso</b><br>
                        <input type="radio" name="estado[]" value="CURSADA" <?php echo (isset($alumno) && $alumno['ESTADO_PRACTICA'] == 'CURSADA') ? 'checked': ''  ?>><b> Cursada</b><br>
                        <input type="radio" name="estado[]" value="NO CURSADA" <?php echo (isset($alumno) && $alumno['ESTADO_PRACTICA'] == 'NO CURSADA') ? 'checked': ''  ?>><b> No Cursada</b><br><br>
                        <label>Comentarios</label>
                        <textarea name="comentario" style='resize: none;' rows='6' cols='6' class="form-control">
                            <?php echo (isset($alumno)) ? $alumno['COMENTARIO_PRACTICA']: ''  ?>
                        </textarea><br>
                        <input type="submit" class='btn btn-success' value="GUARDAR"/>
                        <input type='hidden' name='rutalumno' value='<?php echo (isset($alumno)) ? $alumno['RUT']: '' ?>' />
                    </form>
                </div>
                    
            </div>
            
            
        </div>
    </div>
</div>



<?php include("../templates/footer.php"); ?> 