<?php include("header_admin.php"); ?>
<?php $asignaturas = $db->FAM_SELECT_ASIGNATURAS_ALL_HOMOLOGACION();?>

<script>
    
    $(document).ready(function(){
        $('#pool_asignaturas').DataTable({
            "pagingType": "full_numbers",
            "paging": true,
            "order": [[ 1, "asc" ]],
            "language": {
                "lengthMenu": "Mostrando _MENU_ datos por página.",
                "zeroRecords": "No existen asignaturas en pool.",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "search": "Buscar:",
                "paginate": {
                    "first":      "Primera",
                    "last":       "Última",
                    "next":       "Siguiente",
                    "previous":   "Anterior"
                },
                "infoEmpty": "No hay registros disponibles.",
                "infoFiltered": "(Filtrado de _MAX_ registros totales.)"
            }
        });
        
    });
    
    function trae_asignaturas_plan(){
        var plan = $('#plan').val();

        $.ajax({
            method: "GET",
            url: "procesos_ajax/ajax_plan_asignaturas.php",
            data: { codigo_plan: plan }
            })
            .done(function( msg ) {
                var asignaturas = JSON.parse(msg);
                $('#plan_asignaturas_body').empty();
                $.each(asignaturas, function(index,asignatura){
                    $('#plan_asignaturas_body').append('<tr id="tr-plan'+asignatura.COD_ASIGNATURA+'">');
                    $('#tr-plan'+asignatura.COD_ASIGNATURA+'').append('<td>'+plan+'</td>');
                    $('#tr-plan'+asignatura.COD_ASIGNATURA+'').append('<td>'+asignatura.COD_ASIGNATURA+'</td>');
                    $('#tr-plan'+asignatura.COD_ASIGNATURA+'').append('<td>'+asignatura.NOM_ASIGNATURA+'</td>');
                    $('#tr-plan'+asignatura.COD_ASIGNATURA+'').append('<td onclick="agregarCompararPlan(this);" style="cursor:pointer"><i><span class="glyphicon glyphicon-book text-warning"></span></i></td>');
                    $('#plan_asignaturas_body').append('</tr>');
                });
            })
            .fail(function() {
                alert( "Error en solicitud a servidor.");
        });
    }
    
    function agregarCompararPlan(asignatura){
        var id = $(asignatura).parent().attr('id');
        var count = $("#inicial_drop > p").length;
        
        //Verifica si ya está agregada al listado inicial
        if($(asignatura).parent().hasClass('success')){
            $('#'+id+'drop').remove();
            $(asignatura).parent().toggleClass('success');
        }else{

            //Verifica si ya hay una asignatura como inicial
            if(count < 1){
                $('#inicial_drop').append('<p id="'+id+'drop"><b>'+id.substr(7)+'</b></p>');
                $(asignatura).parent().toggleClass('success');
            }else{
                alert('Sólo puede seleccionar una asignatura como inicial.');
            }
        }
    }
    
    function agregarCompararPool(asignatura){
        var id = $(asignatura).attr('id');
        
        //Verifica si ya está agregada al listado inicial
        if($(asignatura).parent().hasClass('success')){
            $('#'+id+'drop').remove();
            $(asignatura).parent().toggleClass('success');
        }else{
            $('#adicionales_drop').append('<p id="'+id+'drop"><b>'+id+'</b></p>');
            $(asignatura).parent().toggleClass('success');
        }
    }
    
</script>


<!-- ZONA DE CABECERA -->
    <nav class="navbar navbar-default"> 
        <div class="loader"></div>
        <a class="navbar-brand logo" href="../index.php">
            <img alt="Brand"  src="../Imagenes/logo-U.jpg">
        </a>
        <h2>ADMINISTRACIÓN DE HOMOLOGACIONES</h2>    
    </nav>
<!-- FIN ZONA DE CABECERA -->

<div class="container-fluid">
    <div class="row">
        <div class="form-inline col-md-12">
            <label class="col-md-1">Plan de estudio</label>
            <input type="text" class="form-control col-md-1" id="plan" placeholder="ICF120-2014" /><div class="col-md-1"></div>
            <input type="button" class="btn btn-warning col-md-1" value="TRAER PLAN" onclick="trae_asignaturas_plan();" /><div class="col-md-8"></div>
        </div>
        <div class="col-md-12"><hr></div>
        <div class="panel panel-primary col-md-5">
            <div class="panel-heading">
                <h2 class="panel-title">Tabla Plan de Estudio</h2>
            </div>
            <div class="panel-body">
                <table class="table table-responsive table-condensed" id="plan_asignaturas">
                    <thead>
                        <tr>
                            <th>PLAN</th><th>CÓDIGO</th><th>ASIGNATURA</th><th>ADD</th>
                        </tr>
                    </thead>
                    <tbody id="plan_asignaturas_body">
                        
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="panel panel-primary col-md-2">
            <div class="panel-heading">
                <h2 class="panel-title">Homologar</h2>
            </div>
            <div class="panel-body">
                <label>Inicial</label>
                <div id="inicial_drop" class="well well-sm">
                    
                </div>
                <label>Adicionales</label>
                <div id="adicionales_drop" class="well well-sm">
                    
                </div>
            </div>
        </div>
    
        <div class="panel panel-primary col-md-5">
            <div class="panel-heading">
                <h2 class="panel-title">Pool de Asignaturas</h2>
            </div>
            <div class="panel-body">
                <table class="table table-responsive table-condensed" id="pool_asignaturas">
                    <thead>
                        <tr>
                            <th>ADD</th><th>ASIGNATURA</th><th>CÓDIGO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($asignaturas as $asignatura): ?>
                        
                        <tr>
                            <td style="cursor:pointer" id="<?php echo $asignatura['COD_ASIGNATURA']; ?>" onclick="agregarCompararPool(this);"><i><span class="glyphicon glyphicon-book text-success"></span></i></td>
                            <td><?php echo $asignatura['NOM_ASIGNATURA']; ?></td>
                            <td><?php echo $asignatura['COD_ASIGNATURA']; ?></td>
                        </tr>
                        
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
</div>





<?php include("../templates/footer.php"); ?>

