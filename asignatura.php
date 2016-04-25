<?php include("templates/header.php"); ?>
<?php include("templates/navbar.php"); ?>

<?php
    if($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 3 && $_SESSION['perfil'] != 4){
        header("location: index.php");
    }
    
    if(isset($_GET['codigo'])){
        $secciones = $db->FAM_SELECT_SECCIONES_CODIGO_ASIGNATURA($_GET['codigo']);
        if(empty($secciones)){
            echo "<div class='container alert alert-warning'>No Existen Secciones para ese Código de Asignatura</div>";
        }
        
    }
?>

<div class="container-fluid">
    <legend>Secciones Existentes</legend>
    <table class="table table-bordered table-fluid">
        <thead>
            <tr>
                <th>CÓDIDO</th>
                <th>ASIGNATURA</th>
                <th>PROFESOR</th>
                <th>DIA</th>
                <th>INICIO</th>
                <th>TERMINO</th>
                <th>MODALIDAD</th>
                <th>PLAN ESTUDIO</th>
                <th>AÑO</th>
                <th>SEMESTRE</th>
                <th>NIVEL</th>
                <th>VER</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($secciones as $seccion): ?>
            <tr>
                <td class="success"><?php echo $seccion['COD_SECCION']; ?></td>
                <td class="success"><?php echo $seccion['NOM_ASIGNATURA']; ?></td>
                <td class="success"><?php echo $seccion['PROFESOR_NOMBRE']; ?></td>
                <td class="success"><?php echo $seccion['DIA']; ?></td>
                <td class="success"><?php echo $seccion['INICIO']; ?></td>
                <td class="success"><?php echo $seccion['TERMINO']; ?></td>
                <td class="success"><?php echo $seccion['MODALIDAD']; ?></td>
                <td class="success"><?php echo $seccion['PLANESTUDIO_COD_PLANESTUDIO']; ?></td>
                <td class="success"><?php echo $seccion['ANO']; ?></td>
                <td class="success"><?php echo $seccion['SEMESTRE']; ?></td>
                <td class="success"><?php echo $seccion['NIVEL']; ?></td>
                <td class="success"><button class="btn btn-info" onclick="$('#seccion-<?php echo $seccion['ID_SECCION']; ?>').toggle('slow');">VER</button></td>
            </tr>
            <?php $alumnos = $db->FAM_SELECT_ALUMNOS_SECCION($seccion['COD_SECCION'], $seccion['ANO'], $seccion['SEMESTRE']); ?>
            <tr id="seccion-<?php echo $seccion['ID_SECCION']; ?>" style="display: none;">
                <td colspan="12">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>NOMBRES</th><th>RUT</th><th>MATRÍCULA</th><th>CÓDIGO PLAN</th><th>ESTADO</th>
                                <?php if($_SESSION['perfil'] != 3): ?>
                                    <th>IR A ALUMNO</th>
                                <?php endif;?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($alumnos as $alumno): ?>
                            <tr>
                                <td><?php echo $alumno['NOMBRES']; ?></td>
                                <td><?php echo $alumno['RUT']; ?></td>
                                <td><?php echo $alumno['N_MATRICULA']; ?></td>
                                <td><?php echo $alumno['CODIGO_PLAN']; ?></td>
                                <td><?php echo $alumno['ESTADO_ESTUDIO']; ?></td>
                                <?php if($_SESSION['perfil'] != 3): ?>
                                    <td class="center"><button class="btn btn-info" onclick="window.location='alumno.php?rut=<?php echo $alumno['RUT']; ?>';">VER ALUMNO</button></td>
                                <?php endif;?>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>NOMBRES</th><th>RUT</th><th>MATRÍCULA</th><th>CÓDIGO PLAN</th><th>ESTADO</th>
                                <?php if($_SESSION['perfil'] != 3): ?>
                                    <th>IR A ALUMNO</th>
                                <?php endif;?>
                            </tr>
                        </tfoot>
                    </table>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th>COD_SECCION</th>
                <th>NOM_ASIGNATURA</th>
                <th>PROFESOR_NOMBRE</th>
                <th>DIA</th>
                <th>INICIO</th>
                <th>TERMINO</th>
                <th>MODALIDAD</th>
                <th>PLANESTUDIO_COD_PLANESTUDIO</th>
                <th>ANO</th>
                <th>SEMESTRE</th>
                <th>NIVEL</th>
                <th>VER</th>
            </tr>
        </tfoot>
    </table>
</div><br><br>











<?php include("templates/footer.php"); ?> 