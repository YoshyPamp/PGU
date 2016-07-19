<?php include("header_admin.php"); ?>
<?php $planes = $db->FAM_SELECT_PLANES_ESCUELA('ICC-01');?>

<?php
    if(isset($_GET['plan'])){
        $plan_actual = $_GET['plan'];
        $asignaturas = $db->FAM_SELECT_ASIGNATURAS_PLAN($plan_actual,'proyeccion');
    }
?>
    

<script type="text/javascript">
    $(window).load(function() {
        $(".loader").fadeOut("slow");
    })
    
    function borrar_homologacion(id_i,id_a,tr){

        $.ajax({
            method: "GET",
            url: "procesos_ajax/ajax_borrar_homologacion.php",
            data: { id_inicial: id_i, id_adicional: id_a }
        })
        .done(function( msg ) {
            alert(msg);
            location.reload();
        })
        .fail(function() {
            alert( "Error en solicitud a servidor.");
        });
    }
</script>

    <nav class="navbar navbar-default">       
        <a class="navbar-brand logo" href="../index.php">
            <img alt="Brand"  src="../Imagenes/logo-U.jpg">
        </a>
        <h2>TABLA DE HOMOLOGACIONES</h2>    
    </nav>
    <div class="container-fluid">
        <a href="admin_homologaciones.php"><img src="../Imagenes/back.png" width="30px" heigth="30px"></a>
    </div>
    <div class="loader"></div>

    <!-- ZONA DE MENSAJES -->
    <?php
        if(isset($_GET['msg'])){
            echo $_GET['msg'];
        }
    ?>
    
    <div class="container">
        <div class="form-group">
            <form method="GET" action="admin_tabla_homologaciones.php" id="forma">
                <label>Planes de Estudio:</label>
                <select class="form-control" name="plan" onchange="$('#forma').submit();">
                    <option value="">Seleccione Plan...</option>
                    <?php foreach($planes as $plan): ?>
                    <option 
                        <?php echo (isset($plan_actual) && $plan_actual == $plan['COD_PLANESTUDIO']) ? 'selected': '' ?> 
                        value="<?php echo $plan['COD_PLANESTUDIO']; ?>">
                            <?php echo utf8_encode($plan['NOM_PLANESTUDIO'])." | ".$plan['COD_PLANESTUDIO']; ?>
                    </option>
                    <?php endforeach;?>
                </select>
            </form>
            <hr>
            <input class="form-control" name="planactual" value="<?php echo (isset($plan_actual)) ? $plan_actual: ''; ?>" type="text" readonly>
        </div>
        <hr>
        <hr>
        <table class="table table-bordered table-condensed" id='asignaturas_plan'>
            <thead>
                <tr class='info'>
                    <th>NOMBRE ASIGNATURA</th><th>CÓDIGO ASIGNATURA</th><th>HOMOLOGACIONES</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($asignaturas)):?>
                    <?php foreach($asignaturas as $asignatura): ?>
                    <?php if(substr($asignatura['COD_ASIGNATURA'],0,2) != 'FG'):?>
                    <tr>
                        <td><?php echo $asignatura['NOM_ASIGNATURA']; ?></td>
                        <td><?php echo $asignatura['COD_ASIGNATURA']; ?></td>
                        <td>
                            <?php $homologaciones = $db->FAM_SELECT_HOMOLOGACIONES_ASIGNATURA($asignatura['ID_ASIGNATURA']);?>
                            <?php if(count($homologaciones) != 0):?>
                                <input type='button' class='btn btn-primary' value='MOSTRAR' onclick='$("#<?php echo $asignatura['ID_ASIGNATURA']; ?>").toggle("slow");' /><br>
                            <?php else:?>
                                <input type='button' class='btn btn-primary' value='MOSTRAR' disabled /><br>
                            <?php endif;?><br>
                            <table class="table table-bordered table-condensed" style='display: none;' id='<?php echo $asignatura['ID_ASIGNATURA']; ?>'>
                                <tbody>
                                    <?php foreach($homologaciones as $asig_homo):?>
                                    <tr>
                                        <td><?php echo utf8_encode($asig_homo['NOM_ASIGNATURA']); ?></td>
                                        <td><?php echo $asig_homo['COD_ASIGNATURA']; ?></td>
                                        <td><button class='btn btn-danger btn-sm' onclick='borrar_homologacion(<?php echo $asignatura['ID_ASIGNATURA']; ?>, <?php echo $asig_homo['ID_ASIGNATURA']; ?>);'><span class='glyphicon glyphicon-erase'></span></button></td>
                                    </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <?php endif;?>
                    <?php endforeach;?>
                <?php endif;?>
            </tbody>
        </table>
    </div>

    
<?php include("../templates/footer.php"); ?>
