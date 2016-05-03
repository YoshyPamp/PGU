<?php include("header_admin.php"); ?>

<!-- ZONA DE CABECERA -->
    <nav class="navbar navbar-default"> 
        <div class="loader"></div>
        <a class="navbar-brand logo" href="../index.php">
            <img alt="Brand"  src="../Imagenes/logo-U.jpg">
        </a>
        <h2>ADMINISTRADOR DE ALUMNOS</h2>    
    </nav>
<!-- FIN ZONA DE CABECERA -->


<!-- ZONA DE MENSAJES -->
<?php
    if(isset($_GET['msg'])){
        echo $_GET['msg'];
    }
?>
<!-- ZONA DE MENSAJES -->


<!-- ZONA DE BUSQUEDA Y RESULTADO -->
<div class="container">
    <legend>Buscar alumno a modificar.</legend>
    <input type="text" class="form-control" name="rut" id="rut" placeholder="Rut alumno"><br>
    <button class="btn btn-warning" onclick="alumno_ajax();">Buscar</button>
</div><br><br>
<div class="container">
    <form action="procesos_database/actualizar_alumno.php" method="POST" name="modificar_alumno">
        <legend>Datos Alumno</legend>
        <label for="rut">Rut:</label>
        <input type="text" class="form-control" name="rut_alumno" id="rut_alumno" placeholder="Rut" required readonly><br>
        <label for="matricula">Nro. Matrícula:</label>
        <input type="number" class="form-control" name="matricula" id="matricula" placeholder="Nro. Matrícula"><br>
        <label for="nombre">Nombre Alumno:</label>
        <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre" required><br>
        <label for="estado">Estado Estudio:</label>
        <input type="text" class="form-control" name="estado" id="estado" placeholder="Estado estudio" required><br>
        <label for="plan">Código Plan de Estudio:</label>
        <input type="text" class="form-control" name="plan" id="plan" placeholder="Plan de estudio" required><br>
        <input class="btn btn-success" type="submit" value="ACTUALIZAR INFORMACIÓN" >
    </form>
</div>
<!-- FIN ZONA DE BUSQUEDA Y RESULTADO -->


<!-- ZONA DE FUNCIONES JAVASCRIPT -->
<script>
    function alumno_ajax(){
        let $rut = $('#rut').val();
        if($rut === ''){
            alert('Debe Ingresar un rut para la búsqueda');
        }else{
            $.ajax({
                method: "GET",
                url: "procesos_ajax/ajax_alumno.php",
                data: { rut: $rut }
                })
                .done(function( msg ) {
                    let alumno = JSON.parse(msg);
                    
                    if(typeof alumno.RUT !== 'undefined'){
                        $('#rut_alumno').val(alumno.RUT);
                        $('#matricula').val(alumno.N_MATRICULA);
                        $('#nombre').val(alumno.NOMBRES);
                        $('#estado').val(alumno.ESTADO_ESTUDIO);
                        $('#plan').val(alumno.CODIGO_PLAN); 
                    }else{
                        alert(alumno.error);
                    }
                    
                })
                .fail(function() {
                    alert( "Error en solicitud a servidor.");
                });
        }
    }
</script>
<!-- ZONA DE FUNCIONES JAVASCRIPT -->

<?php include("../templates/footer.php"); ?>

