<?php include("header_reportes.php"); ?>
<?php $alumnos = $db->FAM_SELECT_ALUMNOS();?>
<?php 
    $ano = date('Y');

    if($sem == 1){
        $sem = 2;
        $ano = $ano - 1;
    }else{
        $sem = 1;
    }
    
    //Para selección personalizada captura los datos del formulario.
    if(isset($_GET['ano']) && isset($_GET['sem']) ){
        $ano = $_GET['ano'];
        $sem = $_GET['sem'];
    }
?>

<script>
        
    $(document).ready(function(){
        $('#report_norpro').DataTable({
            "pagingType": "full_numbers",
            "paging": false,
            "order": [[ 1, "asc" ]],
            "language": {
                "lengthMenu": "Mostrando _MENU_ datos por página.",
                "zeroRecords": "No existen estudiantes que cumplan con requisitos para ese año y semestre.",
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
        
        
    
    
</script>


<div class="container">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h2 class="panel-title">Informe de Aprobación 100%</h2>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <form method='GET' action='informe_repr_sem.php'>
                    <label>Año: </label>
                    <input type="text" class="form-control" placeholder="2016,2015,2014" name='ano'><br>
                    <label>Semestre: </label>
                    <input type="text" class="form-control" placeholder="1,2" name='sem'><br>
                    <input type="submit" class="btn btn-success" value="MOSTRAR">
                    <br><br>
                </form>
            </div>
            <div class="well">
                <table class="table table-striped table-bordered" id='report_norpro'>
                    <thead>
                        <tr>
                            <th>RUT</th><th>NOMBRE</th><th>SEMESTRE</th><th>AÑO</th><th>APROBACIÓN 100%</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($alumnos as $alumno):?>
                        <?php $NORP = $db->FAM_REPORT_NOREPROBACION_BY_RUT($alumno['RUT'],$sem,$ano);?>
                        <?php if($NORP['NOREPROBACION'] == 'CUMPLE'):?>
                        <tr>
                            <td align='center'><?php echo $alumno['RUT']; ?></td>
                            <td align='center'><?php echo utf8_decode($alumno['NOMBRES']); ?></td>
                            <td align='center'><?php echo $sem; ?></td>
                            <td align='center'><?php echo $ano; ?></td>
                            <td align='center'>CUMPLE</td>
                        </tr>
                        <?php endif;?>
                        <?php endforeach;?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>RUT</th><th>NOMBRE</th><th>SEMESTRE</th><th>AÑO</th><th>APROBACIÓN 100%</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>



<?php include("../templates/footer.php"); ?> 