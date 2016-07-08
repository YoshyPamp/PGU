<?php include("header_reportes.php"); ?> 
<?php $planes = $db->FAM_SELECT_PLANES_ESCUELA('ICC-01');?>

<script>
    function traer_asignaturas(){
        var plan = $('.plan').val(); 
        var ano = $('.ano').val(); 
        var semestre = $('.semestre').val(); 
        
        //Trae asignaturas homologadas
        $.ajax({
            method: "GET",
            url: "procesos_ajax/ajax_homologadas_all.php",
            data: { plan: plan, ano: ano, semestre: semestre }
        })
        .done(function( msg ) {
            console.log(msg);
             var resultado = JSON.parse(msg);
             $('#resultado').empty();
            
            $.each(resultado, function(index,ramo){
                $('#resultado').append('<tr id="'+ramo.COD_SECCION+'">');
                $('#'+ramo.COD_SECCION).append('<td>'+ramo.PLANESTUDIO_COD_PLANESTUDIO+'</td>');
                $('#'+ramo.COD_SECCION).append('<td>'+ramo.COD_SECCION+'</td>');
                $('#'+ramo.COD_SECCION).append('<td>'+ramo.NOM_ASIGNATURA+'</td>');
                $('#'+ramo.COD_SECCION).append('<td>'+ramo.PROFESOR_NOMBRE+'</td>');
                $('#'+ramo.COD_SECCION).append('<td>'+ramo.ANO+'</td>');
                $('#'+ramo.COD_SECCION).append('<td>'+ramo.SEMESTRE+'</td>');
                $('#'+ramo.COD_SECCION).append('<td><input type="button" class="btn btn-primary" value="VER" onclick="window.location=\'../asignatura.php?codigo='+ramo.COD_SECCION.substr(0, 8)+'\'" ></td>');
                $('#resultado').append('</tr>');
            });
        })
        .fail(function() {
            alert( "Error en solicitud a servidor.");
        });
    }
</script>

<div class='container'>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h2 class="panel-title">Homologación de Asignaturas</h2>
        </div>
        <div class="panel-body" >
            <div class="form-group">
                <label>Año: </label>
                <input type="text" class='ano form-control' placeholder="2016, 2015"><br>
                <label>Semestre: </label>
                <input type="text" class='semestre form-control' placeholder="1 o 2"><br>
                <label>Plan de Estudio: </label>
                <select class='plan form-control'>
                    <option value="">Seleccione plan de estudio...</option>
                    <?php foreach($planes as $plan): ?>
                    <option 
                        <?php echo (isset($plan_actual) && $plan_actual == $plan['COD_PLANESTUDIO']) ? 'selected': '' ?> 
                        value="<?php echo $plan['COD_PLANESTUDIO']; ?>">
                            <?php echo utf8_encode($plan['NOM_PLANESTUDIO'])." | ".$plan['COD_PLANESTUDIO']; ?>
                    </option>
                    <?php endforeach;?>
                </select><br>
                <input type="button" value="BUSCAR" onclick='traer_asignaturas();' class="btn btn-warning"/><br><br>
            </div> 
            <hr>
            <div class="panel panel-info col-md-12">
                <div class="panel-heading">
                    <h2 class="panel-title">Asignaturas Plus</h2>
                </div>
                <div class="panel-body">
                    <button class="btn btn-success" onclick="$('#tabla_homologaciones').tableExport({type:'excel'});">Exportar a XLS</button><br><br>
                    <table class="table table-condensed table-bordered" id="tabla_homologaciones">
                        <thead>
                            <tr>
                                <th>PLAN DE ESTUDIO</th>
                                <th>SECCION</th>
                                <th>ASIGNATURA</th>
                                <th>PROFESOR</th>
                                <th>AÑO</th>
                                <th>SEMESTRE</th>
                                <th>VER</th>
                            </tr>
                        </thead>
                        <tbody id='resultado'>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
</div>

    
  

    



<?php include("../templates/footer.php"); ?> 
    