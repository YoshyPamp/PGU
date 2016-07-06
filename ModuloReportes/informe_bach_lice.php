<?php include("header_reportes.php"); ?> 
<?php $alumnos = $db->FAM_SELECT_ALUMNOS();?>

<script>
        
    $(document).ready(function(){
        $('#tb_informe_bach_lic').DataTable({
            "pagingType": "full_numbers",
            "paging": false,
            "order": [[ 1, "asc" ]],
            "language": {
                "lengthMenu": "Mostrando _MENU_ datos por página.",
                "zeroRecords": "No se encuentran registros.",
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
            <h2 class="panel-title">Informe de Bachillerato y Licenciatura</h2>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <button class="btn btn-success" onclick="$('#tb_informe_bach_lic').tableExport({type:'excel'});">Exportar a XLS</button><br><br>
                <table class="table table-striped table-bordered" id="tb_informe_bach_lic">
                    <thead>
                        <tr>
                            <th>RUT</th><th>NOMBRE</th><th>PLAN ESTUDIO</th><th>BACHILLERATO</th><th>LICENCIATURA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($alumnos as $alumno): ?>
                        <?php $resultado = $db->FAM_VERIFICAR_NIVEL_MINIMO_ASIGNATURAS($alumno['CODIGO_PLAN'], $alumno['RUT']); ?>
                            <tr>
                                <td class="rut"><?php echo $alumno['RUT']; ?></td>
                                <td><?php echo utf8_decode($alumno['NOMBRES']); ?></td>
                                <td><?php echo $alumno['CODIGO_PLAN']; ?></td>
                                <?php if($resultado['NIVEL_MINIMO'] > 4): ?>
                                <?php $classB = "success"; $textoB = "CALIFICA"; ?>
                                <?php else:?>
                                <?php $classB = "danger"; $textoB = "NO CALIFICA";?>
                                <?php endif;?>
                                
                                <?php if($resultado['NIVEL_MINIMO'] > 8): ?>
                                <?php $classL = "success"; $textoL = "CALIFICA"; ?>
                                <?php else:?>
                                <?php $classL = "danger"; $textoL = "NO CALIFICA";?>
                                <?php endif;?>
                                <td class="<?php echo $classB; ?>"><?php echo $textoB; ?></td>
                                <td class="<?php echo $classL; ?>"><?php echo $textoL; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>RUT</th><th>NOMBRE</th><th>PLAN ESTUDIO</th><th>BACHILLERATO</th><th>LICENCIATURA</th>
                        </tr>
                    </tfoot>
                </table>
            </div> 
        </div>
    </div>
</div>



<?php include("../templates/footer.php"); ?> 
    