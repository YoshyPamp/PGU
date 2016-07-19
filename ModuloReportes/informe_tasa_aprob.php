<?php include("header_reportes.php"); ?>
<?php $docentes = $db->FAM_SELECT_DOCENTES();?>
<?php $asignaturas = $db->FAM_SELECT_CODIGOS_ASIGNATURAS_EN_SECCIONES();?>
<?php $tasas_general = $db->FAM_REPORT_TASAAPROBADOS_GENERAL('ICC-01');?>

<script>
    function bloquear(x){
        if(x.id === "docente"){
            $('.asignatura').attr("disabled","disabled");
            $('.docente').removeAttr("disabled");
            $('.asignatura').val("");
        }else{
            $('.docente').attr("disabled","disabled");
            $('.asignatura').removeAttr("disabled");
            $('.docente').val("");
        }
    }
    
    function trae_tasas(){
            var ano = $('#ano').val();
            var sem = $('#semestre').val();
            var docente = $('.docente').val();
            var asignatura = $('.asignatura').val();
            var escuela = "ICC-01";
            
            if(sem == "" || ano == ""){
                alert('Debe ingresar año y semestre obligatoriamente.');
            }
            
            if(docente == "" && asignatura == ""){
                alert('Debe seleccionar al menos un docente o una asignatura.');
            }else{
                console.log("AÑO: "+ano+" SEMESTRE: "+sem+" DOCENTE: "+docente+" ASIGNATURA: "+asignatura+" ESCUELA: "+escuela);
            
                $.ajax({
                    method: "GET",
                    url: "procesos_ajax/ajax_select_tasas_apro_repr.php",
                    data: { ano: ano, semestre: sem, docente: docente, asig: asignatura, escu: escuela }
                })
                .done(function( msg ) {
                    console.log(msg);
                    if(msg.length > 5){
                        var tasas = JSON.parse(msg);
                        
                        $('#resultado_busqueda').empty();
                        $('#resultado_busqueda').append('<thead id="head">');
                        $('#head').append('<tr id="headtr">');
                        $('#headtr').append('<th>AÑO</th><th>SEMESTRE</th><th>ASIGNATURA/DOCENTE</th><th>TASA APROBADOS</th><th>TASA REPROBADOS</th><th>ESCUELA</th>');
                        $('#head').append('</tr>');
                        $('#resultado_busqueda').append('</thead>');
                        $('#resultado_busqueda').append('<tbody id="body">');
                        $('#body').append('<tr id="bodytr">');
                        $('#bodytr').append('<td align="center">'+ano+'</td>');
                        $('#bodytr').append('<td align="center">'+sem+'</td>');
                        if(docente.length == 0){
                            $('#bodytr').append('<td align="center">'+asignatura+'</td>');
                        }else{
                            $('#bodytr').append('<td align="center">'+docente+'</td>');
                        }
                        $('#bodytr').append('<td align="center" class="success">'+tasas.TASA_APROBADOS+'</td>');
                        $('#bodytr').append('<td align="center" class="danger">'+tasas.TASA_REPROBADOS+'</td>');
                        $('#bodytr').append('<td align="center" class="primary">'+escuela+'</td>');
                        $('#body').append('</tr>');
                        $('#resultado_busqueda').append('</tbody>');
                    }else{
                        alert('NO EXISTEN ASIGNATURAS QUE CALCULAR PARA ESA BÚSQUEDA');
                    }
                    
                })
                .fail(function() {
                    alert( "Error en solicitud a servidor.");
                });
            }//Final if 
    }
</script>

<div class="container">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h2 class="panel-title">Informe de Tasa de Aprobación</h2>
        </div>
        <div class="panel-body">
            
            <!-- PANEL CON FORMULARIO PARA BUSCAR -->
            <div class="form-group">
                <label>Año: </label>
                <input type="text" class="form-control" placeholder="2016,2015,2014" id='ano'><br>
                <label>Semestre: </label>
                <select id='semestre' class='form-control'>
                    <option value=''>Seleccione Semestre...</option>
                    <option value='1'>Primer Semestre</option>
                    <option value='2'>Segundo Semestre</option>
                </select><br>
                <hr>
                <ul class="nav nav-pills">
                    <li><input type='button' class='btn btn-warning' id='docente' onclick='bloquear(this)' value='Tasas Docente' /></li>
                    <li><input type='button' class='btn btn-warning' id='asignatura' onclick='bloquear(this)' value='Tasas Asignatura' /></li>
                </ul>
                <hr>
                <label>Docente: </label>
                <select disabled name='docente' class='form-control docente'>
                    <option value=''>Seleccione Docente...</option>
                    <?php foreach($docentes as $docente):?>
                    <option value='<?php echo utf8_encode($docente['PROFESOR_NOMBRE']); ?>'><?php  echo utf8_encode($docente['PROFESOR_NOMBRE']); ?></option>
                    <?php endforeach;?>
                </select><br>
                <label>Asignatura: </label>
                <select disabled name='asignatura' class='form-control asignatura'>
                    <option value=''>Seleccione Asignatura...</option>
                    <?php foreach($asignaturas as $asignatura):?>
                        <option value='<?php echo $asignatura['COD_ASIGNATURA']; ?>'><?php  echo utf8_encode($asignatura['NOM_ASIGNATURA'])." / ".$asignatura['COD_ASIGNATURA']; ?></option>
                    <?php endforeach;?>
                </select><br>
                <input type="button" onclick='trae_tasas();' class="btn btn-success" value="MOSTRAR TASAS">
                <br><br>
            </div>
            <!-- FINAL FORMULARIO PARA BUSCAR -->
            <div>
                <button class="btn btn-success" onclick="$('#resultado_busqueda').tableExport({type:'excel'});">Exportar a XLS</button><br><br>
                <table class='table table-striped table-bordered' id='resultado_busqueda'>
                    <thead>
                        <tr>
                            <th>TASA DE APROBACIÓN GLOBAL</th><th>TASA DE REPROBACIÓN GLOBAL</th><th>ESCUELA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td align='center' class='success'><?php echo (isset($tasas_general['TASA_APROBADOS'])) ? $tasas_general['TASA_APROBADOS']: '';  ?></td>
                            <td align='center' class='danger'><?php echo (isset($tasas_general['TASA_REPROBADOS'])) ? $tasas_general['TASA_REPROBADOS']: '';  ?></td>
                            <td align='center' class='primary'>ICC-01</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>





<?php include("../templates/footer.php"); ?> 
