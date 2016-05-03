<?php include("header_admin.php"); ?>
    <nav class="navbar navbar-default">  
        <div class="loader"></div>
        <a class="navbar-brand logo" href="../index.php">
            <img alt="Brand"  src="../Imagenes/logo-U.jpg">
        </a>
        <h2>ADMINISTRADOR DE PLANES DE ESTUDIO</h2>    
    </nav>

<!-- ZONA DE MENSAJES -->
<?php
    if(isset($_GET['msg'])){
        echo $_GET['msg'];
    }
?>
<div id="mensaje"></div>
<!-- FIN ZONA DE MENSAJES -->

<div class="container">
    <legend>Buscador de Planes</legend>
    <label>Código</label>
    <input type="text" class="form-control" name="codigo" id="codigo" placeholder="ICV110-2015"><br>
    <button type="button" class="btn btn-warning" onclick="plan_ajax();">BUSCAR</button>
</div>
<hr>

<!-- RESULTADO -->
<div class="container">
    
    <button class="btn btn-success form-control" onclick="actualiza_datos_plan();" id="boton_grabar_plan" disabled>ACTUALIZAR DATOS PLAN DE ESTUDIO</button><br><br>
    <button class="btn btn-warning form-control" onclick="asignaturas_ajax();" id="boton_estructura" disabled>MOSTRAR ESTRUCTURA DE ASIGNATURAS</button><br><br>
    <legend>Plan de Estudio</legend>
    <label>Código</label>
    <input type="text" class="form-control" name="codigo_plan" id="codigo_plan" placeholder="ICV110-2015" required readonly><br>
    <label>Nombre</label>
    <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre plan de estudio.." required><br>
    <label>Grado Bachiller</label>
    <input type="text" class="form-control" name="grd_bac" id="grd_bac" placeholder="Grado bachiller.." required><br>
    <label>Grado Académico</label>
    <input type="text" class="form-control" name="grd_aca" id="grd_aca" placeholder="Grado académico.." required><br>
    <label>Título</label>
    <input type="text" class="form-control" name="titulo" id="titulo" placeholder="Titulo profesional" required><br>
    <label>Tipo Plan</label>
    <input type="text" class="form-control" name="tipo" id="tipo" placeholder="Pregrado, Postgrado etc.." required><br>
    <label>Duracion</label>
    <input type="number" class="form-control" name="duracion" id="duracion" placeholder="Numero de semestres." required><br><br>
    
    <hr>
    
    <form action="procesos_database/actualizar_asignaturas.php" method="post">
        <button type="submit" class="btn btn-success center-block form-control" id="btn_asignaturas" disabled>ACTUALIZAR ASIGNATURAS DE PLAN</button><br><br>
        <legend class="asignaturas_plan" style="display: none;">Asignaturas de Plan de Estudio</legend>
        <table class="table table-bordered asignaturas_plan text-center" id="asignaturas_plan" style="display: none;">
            <thead>
                <tr>
                    <th>ID</th><th>CÓDIGO</th><th>NOMBRE</th><th>NIVEL</th>
                </tr>
            </thead>
            <tbody id="body_asignaturas">

            </tbody>
            <tfoot>
                <tr>
                    <th>ID</th><th>CÓDIGO</th><th>NOMBRE</th><th>NIVEL</th>
                </tr>
            </tfoot>
        </table>
        <input type="hidden" name="codigo_plan_hidden" id="codigo_plan_hidden">
    </form>
</div>
<!-- FIN RESULTADO -->

<!-- ZONA DE FUNCIONES JAVASCRIPT -->
<script type="text/javascript">
    
    function plan_ajax(){
        let codigo = $('#codigo').val();
        if(codigo === ''){
            alert('Debe Ingresar un código para la búsqueda');
        }else{
            $.ajax({
                method: "GET",
                url: "procesos_ajax/ajax_plan.php",
                data: { codigo_plan: codigo }
                })
                .done(function(msg) {
                    let plan_estudio = JSON.parse(msg);
                    
                    if(typeof plan_estudio.COD_PLANESTUDIO !== 'undefined'){
                        $('#codigo_plan').val(plan_estudio.COD_PLANESTUDIO);
                        $('#nombre').val(plan_estudio.NOM_PLANESTUDIO);
                        $('#grd_bac').val(plan_estudio.GRD_BACH);
                        $('#grd_aca').val(plan_estudio.GRD_ACAD);
                        $('#titulo').val(plan_estudio.TITULO);
                        $('#tipo').val(plan_estudio.TIPO_PLAN);
                        $('#duracion').val(plan_estudio.DURACION);
                        
                        $('#codigo_plan_hidden').val(plan_estudio.COD_PLANESTUDIO);
                        $('#boton_estructura').removeAttr("disabled");
                        $('#boton_grabar_plan').removeAttr("disabled");
                    }else{
                        alert(plan_estudio.error);
                    }
                })
                .fail(function() {
                    alert( "Error en solicitud a servidor.");
                });
        }
    }
    
    function asignaturas_ajax(){
        let codigo = $('#codigo').val();
        if(codigo === ''){
            alert('Debe Ingresar un código para la búsqueda');
        }else{
            $.ajax({
                method: "GET",
                url: "procesos_ajax/ajax_plan_asignaturas.php",
                data: { codigo_plan: codigo }
                })
                .done(function(msg) {
                    let plan_asignaturas = JSON.parse(msg);
                    $('#body_asignaturas').empty();
                    $.each(plan_asignaturas, function(index,value){
                        $('#body_asignaturas').append("<tr>");
                        $.each(value, function(index1, value1){
                            if(index1 === 'ID_ASIGNATURA'){
                                $('#body_asignaturas')
                                    .append("<td>"+value1+"</td>");
                            }else{
                                $('#body_asignaturas')
                                    .append("<td><input type='text' name='"+value.ID_ASIGNATURA+"[]' class='form-control' value='"+value1+"' ></td>");
                            }
                        });
                        $('#body_asignaturas').append("</tr>");
                    });
                    $('.asignaturas_plan').css('display', 'inline-block');
                    $('#asignaturas_plan').css('display', 'table');
                    $('#btn_asignaturas').removeAttr('disabled');
                    window.location = '#asignaturas_plan';
                })
                .fail(function() {
                    alert( "Error en solicitud a servidor.");
                });
        }
    }
    
    function actualiza_datos_plan(){
        let codigo_plan = $('#codigo_plan').val();
        let nombre = $('#nombre').val();
        let grd_bac = $('#grd_bac').val();
        let grd_aca = $('#grd_aca').val();
        let titulo = $('#titulo').val();
        let tipo = $('#tipo').val();
        let duracion = $('#duracion').val();
        
        if(codigo_plan === '' || nombre === '' || grd_bac === '' || grd_aca === '' 
               || titulo === '' || tipo === '' || duracion === ''){ alert('Debe completar todos los datos del plan.');
        }else{
            $.ajax({
                method: "POST",
                url: "procesos_ajax/ajax_actualizar_datos_plan.php",
                data: { 
                    codigo_plan: codigo_plan,
                    nombre: nombre,
                    grd_bac: grd_bac,
                    grd_aca: grd_aca,
                    titulo: titulo,
                    tipo: tipo,
                    duracion: duracion
                    }
                })
                .done(function(msg) {
                    $('#mensaje').append(msg);
                })
                .fail(function() {
                    alert("Error en solicitud a servidor.");
                });
        }
    }
    
</script>
<!-- ZONA DE FUNCIONES JAVASCRIPT -->

<?php include("../templates/footer.php"); ?>
