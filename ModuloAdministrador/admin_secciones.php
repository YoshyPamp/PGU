<?php include("header_admin.php"); ?>
    <nav class="navbar navbar-default">       
        <a class="navbar-brand logo" href="../index.php">
            <img alt="Brand"  src="../Imagenes/logo-U.jpg">
        </a>
        <h2>ADMINISTRADOR DE SECCIONES</h2>    
    </nav>

    <!-- ZONA DE MENSAJES -->
    <?php
        if(isset($_GET['msg'])){
            echo $_GET['msg'];
        }
    ?>
    
    <script>
        $(document).ready(function() {
            $('#secciones_table').DataTable( {
                   "pagingType": "full_numbers",
                   "order": false,
                   "language": {
                       "lengthMenu": "Mostrando _MENU_ datos por página.",
                       "zeroRecords": "No se encuentran registros.",
                       "info": "Mostrando página _PAGE_ de _PAGES_",
                       "search": "Buscar",
                       "paginate": {
                           "first":      "Primera",
                           "last":       "Última",
                           "next":       "Siguiente",
                           "previous":   "Anterior"
                       },
                       "infoEmpty": "No hay más páginas.",
                       "infoFiltered": "(Filtrado de _MAX_ registros totales.)"
                   },
                   "paginate": false

           } );
        });
        
        $(document).ready(function() {
            $('#alumnos_table').DataTable( {
                   "pagingType": "full_numbers",
                   "order": false,
                   "language": {
                       "lengthMenu": "Mostrando _MENU_ datos por página.",
                       "zeroRecords": "No se encuentran registros.",
                       "info": "Mostrando página _PAGE_ de _PAGES_",
                       "search": "Buscar",
                       "paginate": {
                           "first":      "Primera",
                           "last":       "Última",
                           "next":       "Siguiente",
                           "previous":   "Anterior"
                       },
                       "infoEmpty": "No hay más páginas.",
                       "infoFiltered": "(Filtrado de _MAX_ registros totales.)"
                   },
                   "paginate": false

           } );
        });
        
        function mandar_seccion_modal(seccion){
            $('#modal_seccion').val(seccion);
        }
        
        
        function trae_secciones_alumno(){
            $('#resultado_secciones').show('slow');
            var rut_alumno = $('#rut').val();
            
            $.ajax({
                method: "GET",
                url: "procesos_ajax/ajax_select_secciones_alumno.php",
                data: { rut: rut_alumno }
                })
                .done(function( msg ) {
                    console.log(msg);
                    var secciones = JSON.parse(msg);
                    $('#body_secciones').empty();
                    $.each(secciones, function(indice, seccion){
                        $('#body_secciones').append('<tr>');
                        $('#body_secciones').append('<td>'+seccion.ID_SECCION+'</td>');
                        $('#body_secciones').append('<td>'+seccion.COD_SECCION+'</td>');
                        $('#body_secciones').append('<td>'+seccion.PROFESOR_NOMBRE+'</td>');
                        $('#body_secciones').append('<td>'+seccion.ANO+'</td>');
                        $('#body_secciones').append('<td>'+seccion.SEMESTRE+'</td>');
                        $('#body_secciones').append('</tr>');
                    });
                })
                .fail(function() {
                    alert( "Error en solicitud a servidor.");
            });
        }
        
        function trae_alumnos_seccion(){
            $('#resultado_alumnos').show('slow');
            var seccion = $('#cod_sec').val();
            var ano = $('#ano').val();
            var sem = $('#sem').val();
            
            $.ajax({
                method: "GET",
                url: "procesos_ajax/ajax_select_alumnos_seccion.php",
                data: { cod_sec: seccion, ano: ano, semestre: sem }
                })
                .done(function( msg ) {
                    var alumnos = JSON.parse(msg);
                    console.log(alumnos);
                    $('#body_alumnos').empty();
                    $.each(alumnos, function(indice, alumno){
                        $('#body_alumnos').append('<tr>');
                        $('#body_alumnos').append('<td>'+alumno.NOMBRES+'</td>');
                        $('#body_alumnos').append('<td>'+alumno.CODIGO_PLAN+'</td>');
                        $('#body_alumnos').append('<td>'+alumno.N_MATRICULA+'</td>');
                        $('#body_alumnos').append('<td>'+alumno.RUT+'</td>');
                        $('#body_alumnos').append('<td>'+alumno.ESTADO_ESTUDIO+'</td>');
                        $('#body_alumnos').append('</tr>');
                    });
                })
                .fail(function() {
                    alert( "Error en solicitud a servidor.");
            });
        }
        
    
    </script>
    

    <div class="container text-center">
        <button class="btn btn-warning" onclick="$('#secciones').toggle('slow');">VER SECCIONES VINCULADAS A ALUMNO</button><br><br>
        <button class="btn btn-warning" onclick="$('#alumnos').toggle('slow');">VER ALUMNOS VINCULADOS A SECCION</button>
    </div><br><br><br>

    <hr>

    <div id="secciones" style="display: none" class="container" >
        <fieldset>
            <legend>Buscar Alumno</legend>
            <label for="rut">Rut Alumno</label>
            <input type="text" class="form-control" name="rut" id="rut" placeholder="Ej: 111111111"><br>
            <button class="btn btn-info center" onclick="trae_secciones_alumno();">BUSCAR</button><br><br>

            <div id="resultado_secciones" style="display: none">
                <table class="table table-bordered" id="secciones_table">
                    <thead>
                        <tr>
                            <th>ID SECCION</th><th>CÓDIGO</th><th>PROFESOR</th><th>SEMESTRE</th><th>AÑO</th>
                        </tr>
                    </thead>
                    <tbody id="body_secciones">
                        
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>ID SECCION</th><th>CÓDIGO</th><th>PROFESOR</th><th>SEMESTRE</th><th>AÑO</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </fieldset>
    </div><br><br>
    <div id="alumnos" style="display: none" class="container">
        <fieldset>
            <legend>Buscar Sección</legend>
            <label for="cod_sec">Código Sección</label>
            <input type="text" class="form-control" name="cod_sec" id="cod_sec" placeholder="Ej: ICF123-007"><br>
            <label for="ano">Año</label>
            <input type="number" class="form-control" name="ano" id="ano" placeholder="Ej: 2016"><br>
            <label for="sem">Semestre</label>
            <input type="number" class="form-control" name="sem" id="sem" placeholder="Ej: 1 o 2"><br>
            <button class="btn btn-info center" onclick="trae_alumnos_seccion();">BUSCAR</button><br><br>

            <div id="resultado_alumnos" style="display: none">
                <table class="table table-bordered" id="alumnos_table">
                    <thead>
                        <tr>
                            <th>ALUMNO</th><th>CÓDIGO</th><th>MATRÍCULA</th><th>RUT</th><th>ESTADO</th>
                        </tr>
                    </thead>
                    <tbody id="body_alumnos">
                        
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>ALUMNO</th><th>CÓDIGO</th><th>MATRÍCULA</th><th>RUT</th><th>ESTADO</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </fieldset>
    </div>


<?php include("../templates/footer.php"); ?>
