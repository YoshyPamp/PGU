<?php include("header_reportes.php"); ?>
<?php 
    $disabled = '';
    if(isset($_GET['rut'])){
        $alumno = $db->FAM_SELECT_ALUMNO_RUT($_GET['rut']);
        $asignaturas = $db->FAM_SELECT_ASIGNATURAS_PRACTICA($_GET['rut']);
        if(isset($asignaturas[0], $asignaturas[1])){
            $pra1_nota = $db->FAM_SELECT_NOTA_PRACTICA_BY_COD($asignaturas[0]['COD_ASIGNATURA'], $_GET['rut']);
            $pra2_nota = $db->FAM_SELECT_NOTA_PRACTICA_BY_COD($asignaturas[1]['COD_ASIGNATURA'], $_GET['rut']);
            $disabled = '';
        }else{
            $disabled = 'disabled';
            $pra1_nota = $db->FAM_SELECT_NOTA_PRACTICA_BY_COD($asignaturas[0]['COD_ASIGNATURA'], $_GET['rut']);
        }
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
                        <div class='col-md-6'>
                            <h2 class="panel-title"><b>Práctica 1 - </b><i><?php echo isset($asignaturas[0]['COD_ASIGNATURA']) ? $asignaturas[0]['COD_ASIGNATURA'] : 'NO APLICA' ?></i></h2><br>
                            <input type="radio" onclick="$('#nota').removeAttr('required');" name="estado" value="EN CURSO" <?php echo (isset($alumno) && $alumno['ESTADO_PRACTICA'] == 'EN CURSO') ? 'checked': ''  ?>><b> En curso</b><br>
                            <input type="radio" onclick="$('#nota').attr('required','required');" name="estado" value="CURSADA" <?php echo (isset($alumno) && $alumno['ESTADO_PRACTICA'] == 'CURSADA') ? 'checked': ''  ?>><b> Cursada</b><br>
                            <input type="radio" onclick="$('#nota').removeAttr('required');" name="estado" value="NO CURSADA" <?php echo (isset($alumno) && $alumno['ESTADO_PRACTICA'] == 'NO CURSADA') ? 'checked': ''  ?>><b> No Cursada</b><br><br>
                            <input type="number" style="width: 100px;" class="form-control" id="nota" value="<?php echo isset($pra1_nota[0]['NOTA']) ? $pra1_nota[0]['NOTA'] : '' ; ?>" name="nota" placeholder="Ej: 7.0"><br><br>
                            <label>Comentarios</label>
                            <textarea name="comentario" style='resize: none;' rows='6' class="form-control"><?php echo (isset($alumno)) ? $alumno['COMENTARIO_PRACTICA']: ''  ?></textarea><br>
                        </div>
                        <div class='col-md-6'>
                            <h2 class="panel-title"><b>Práctica 2 - </b><i><?php echo isset($asignaturas[1]['COD_ASIGNATURA']) ? $asignaturas[1]['COD_ASIGNATURA'] : 'NO APLICA' ?></i></h2><br>
                            <input <?php echo $disabled; ?> type="radio" onclick="$('#nota2').removeAttr('required');" name="estado2" value="EN CURSO" <?php echo (isset($alumno) && $alumno['ESTADO_PRACTICA_PRO'] == 'EN CURSO') ? 'checked': ''  ?>><b> En curso</b><br>
                            <input <?php echo $disabled; ?> type="radio" onclick="$('#nota2').attr('required','required');" name="estado2" value="CURSADA" <?php echo (isset($alumno) && $alumno['ESTADO_PRACTICA_PRO'] == 'CURSADA') ? 'checked': ''  ?>><b> Cursada</b><br>
                            <input <?php echo $disabled; ?> type="radio" onclick="$('#nota2').removeAttr('required');" name="estado2" value="NO CURSADA" <?php echo (isset($alumno) && $alumno['ESTADO_PRACTICA_PRO'] == 'NO CURSADA') ? 'checked': ''  ?>><b> No Cursada</b><br><br>
                            <input <?php echo $disabled; ?> type="number" style="width: 100px;" class="form-control" id="nota2" value="<?php echo isset($pra2_nota[0]['NOTA']) ? $pra2_nota[0]['NOTA'] : '' ; ?>" name="nota2" placeholder="Ej: 7.0"><br><br>
                            
                            <label>Comentarios</label>
                            <textarea <?php echo $disabled; ?> name="comentario2" style='resize: none;' rows='6' class="form-control"><?php echo (isset($alumno)) ? $alumno['COMENTARIO_PRACTICA_PRO']: ''  ?></textarea><br>
                        </div>
                        <p><b>Nota: Recuerde que al momento de guardar la práctica como cursada no se puede volver a atras.</b></p>
                        <input type="submit" class='btn btn-success' value="GUARDAR"/>
                        <input type='hidden' name='rutalumno' value='<?php echo (isset($alumno)) ? $alumno['RUT']: '' ?>' />
                    </form>
                </div>
                    
            </div>
            
            
        </div>
    </div>
</div>



<?php include("../templates/footer.php"); ?> 