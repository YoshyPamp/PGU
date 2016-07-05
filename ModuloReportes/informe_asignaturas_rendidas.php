<?php include("header_reportes.php"); ?> 

<script>
    
<<<<<<< HEAD
=======
   
>>>>>>> 23c97cdbca27cd8152b48154e134370e892c1341
    
    function traer_alumno(){
        var rut_alumno = $('#rut').val(); 
        
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
        
        //Trae asignaturas rendidas
        $.ajax({
            method: "GET",
            url: "procesos_ajax/ajax_rendidas.php",
            data: { rut: rut_alumno }
        })
        .done(function( msg ) {
             var resultado = JSON.parse(msg);
             $('#resultado').empty();
            
            $.each(resultado, function(index,ramo){
                $('#resultado').append('<tr id="'+ramo.COD_SECCION+'">');
                $('#'+ramo.COD_SECCION).append('<td>'+ramo.NIVEL+'</td>');
                $('#'+ramo.COD_SECCION).append('<td>'+ramo.NOM_ASIGNATURA+'</td>');
                $('#'+ramo.COD_SECCION).append('<td>'+ramo.COD_SECCION+'</td>');
                $('#'+ramo.COD_SECCION).append('<td>'+ramo.ANO+'</td>');
                $('#'+ramo.COD_SECCION).append('<td>'+ramo.SEMESTRE+'</td>');
                $('#'+ramo.COD_SECCION).append('<td>'+ramo.ESTADO+'</td>');
                $('#'+ramo.COD_SECCION).append('<td>'+ramo.NOTA+'</td>');
                $('#resultado').append('</tr>');
            });
        })
        .fail(function() {
            alert( "Error en solicitud a servidor.");
        });
    }
    
</script>

<div class="container">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h2 class="panel-title">Informe de Asignaturas Rendidas</h2>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label>Rut: </label>
                <input type="text" class="form-control" id='rut'><br>
                <input class="btn btn-warning" type="button" value="BUSCAR" onclick='traer_alumno();'/><br><br>
                <label>Nombre: </label>
                <input type="text" class="form-control" id='nombre' readonly><br>
                <label>Plan Estudio: </label>
                <input type="text" class="form-control" id='plan' readonly><br>
                <label>Nivel de Avance: </label>
                <input type="text" class="form-control" id='nivel' readonly><br>
            </div>
            <hr>
            <table class='table table-bordered table-striped' id='rendidas'>
                <thead>
                    <tr>
                        <th>NIVEL</th><th>NOMBRE</th><th>CÓDIGO</th><th>AÑO</th><th>SEMESTRE</th><th>ESTADO</th><th>NOTA</th>
                    </tr>
                </thead>
                <tbody id='resultado'>
                    
                </tbody>
            </table>
        </div>
    </div>
    
</div>


<?php include("../templates/footer.php"); ?> 