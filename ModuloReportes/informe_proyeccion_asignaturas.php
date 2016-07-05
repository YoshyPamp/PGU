<?php include("header_reportes.php"); ?> 

<script>
        
<<<<<<< HEAD
=======
    
>>>>>>> 23c97cdbca27cd8152b48154e134370e892c1341
    function trae_proyeccion_alumno(){
        var rut_alumno = $('#rut').val();
        var alumno;

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
            $('#nombre').val(alumno.NOMBRES);
            $('#nivel').val(alumno.NIVEL);
                
        })
        .fail(function() {
            alert( "Error en solicitud a servidor.");
        });
        
        $.ajax({
            method: "GET",
            url: "procesos_ajax/ajax_proyeccion_asignaturas.php",
            data: { rut: rut_alumno }
        })
        .done(function( msg ) {
            var proyeccion = JSON.parse(msg);
            $('#proyeccionbody').empty();
            
            $.each(proyeccion, function(index,seccion){
                var color;
                
                $('#proyeccionbody').append('<tr id="'+seccion.COD_ASIGNATURA+'">');
                $('#'+seccion.COD_ASIGNATURA).append('<td>'+seccion.COD_ASIGNATURA+'</td>');
                $('#'+seccion.COD_ASIGNATURA).append('<td>'+seccion.NOM_ASIGNATURA+'</td>');
                $('#'+seccion.COD_ASIGNATURA).append('<td>'+seccion.NIVEL+'</td>');
                $('#proyeccionbody').append('</tr>');
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
            <h2 class="panel-title">Proyección de Asignaturas</h2>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label>Rut: </label>
                <input type="text" class="form-control" id="rut"><br>
                <input class="btn btn-warning" type="button" onclick="trae_proyeccion_alumno();" value="BUSCAR"/><br><br>
                <label>Nombre: </label>
                <input type="text" class="form-control" id="nombre" readonly><br>
                <label>Plan Estudio: </label>
                <input type="text" class="form-control" id="plan" readonly><br>
                <label>Nivel de Avance: </label>
                <input type="text" class="form-control" id="nivel" readonly><br>
            </div>
            <hr>
            <legend>Asignaturas</legend>
            <table class="table table-striped table-bordered" id="proyeccion" >
                <thead>
                    <tr>
                        <th>CÓDIGO</th><th>NOMBRE</th><th>NIVEL</th>
                    </tr>
                </thead>
                <tbody id="proyeccionbody">
                    
                </tbody>
            </table>
        </div>
    </div>
    
</div>

<?php include("../templates/footer.php"); ?> 