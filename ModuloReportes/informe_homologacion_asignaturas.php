<?php include("header_reportes.php"); ?> 

<script>
    function traer_alumno(){
        var rut_alumno = $('.rut').val(); 
        
        //Trae datos alumno
        $.ajax({
            method: "GET",
            url: "procesos_ajax/ajax_alumno.php",
            data: { rut: rut_alumno }
        })
        .done(function( msg ) {
            alumno = JSON.parse(msg);
            if(alumno.length == 0){
                alert("NO EXISTE ALUMNO CON ESE RUT.");
            }
            $('#nombre').val(alumno.NOMBRES);
            $('#plan').val(alumno.CODIGO_PLAN);
            $('#nivel').val('NIVEL '+alumno.NIVEL);
        })
        .fail(function() {
            alert( "Error en solicitud a servidor.");
        });
        
        //Trae asignaturas homologadas
        $.ajax({
            method: "GET",
            url: "procesos_ajax/ajax_homologadas.php",
            data: { rut: rut_alumno }
        })
        .done(function( msg ) {
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
                $('#'+ramo.COD_SECCION).append('<td>'+ramo.NIVEL+'</td>');
                $('#'+ramo.COD_SECCION).append('<td>'+ramo.NOTA+'</td>');
                $('#'+ramo.COD_SECCION).append('<td>'+ramo.ESTADO+'</td>');
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
                <label>Rut: </label>
                <input type="text" class='rut form-control'><br>
                <input type="button" value="BUSCAR" onclick='traer_alumno();' class="btn btn-warning"/><br><br>
                <label>Nombre: </label>
                <input type="text" id='nombre' class="form-control" readonly><br>
                <label>Plan Estudio: </label>
                <input type="text" id='plan' class="form-control" readonly><br>
                <label>Nivel de Avance: </label>
                <input type="text" id='nivel' class="form-control" readonly><br>
            </div> 
            <hr>
            <div class="panel panel-info col-md-12">
                <div class="panel-heading" data-toggle="collapse" href="#collapseHorario" aria-controls="collapseHorario">
                    <h2 class="panel-title">Asignaturas Homologadas <b><i>Presione para ver</i></b></h2>
                </div>
                <div class="panel-body collapse" id='collapseHorario'>
                    <table class="table table-condensed table-bordered" >
                        <thead>
                            <tr>
                                <th>PLAN DE ESTUDIO</th>
                                <th>SECCION</th>
                                <th>ASIGNATURA</th>
                                <th>PROFESOR</th>
                                <th>AÑO</th>
                                <th>SEMESTRE</th>
                                <th>NIVEL</th>
                                <th>NOTA</th>
                                <th>ESTADO</th>
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
    