<?php include("templates/header.php"); ?>
<?php include("templates/navbar.php"); ?>
<?php

    foreach (glob("/ModuloImportador/clases/*.php") as $filename)
    {
        include $filename;
    }
    
    if($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 2 && $_SESSION['perfil'] != 4){
        header("location: index.php");
    }
    
    //Trae los planes de tabla otracarrera
    $planes = $db->FAM_SELECT_OTROSPLANES_BYRUT($_GET['rut']);
    //var_dump($planes);

?>

<script>
    $(document).ready(function(){
        $('#historico_alumno_otroplan').DataTable( {
            "pagingType": "full_numbers",
            "paging":false,
            "order": [[ 1, "desc" ]],
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
    <div class="col-md-3">
        <a href="alumno.php?rut=<?php echo $_GET['rut']; ?>"><img src="Imagenes/back.png" width="30px" heigth="30px"></a>
    </div>
    
    
    <div class="panel panel-info">
        <div class="panel-heading">
            <h2 class="panel-title">Planes cursados anteriormente.</h2>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <button class="btn btn-success" onclick="$('#tb_informe_planes_antiguos').tableExport({type:'excel'});">Exportar a XLS</button><br><br>
                <table class="table table-striped table-bordered" id="tb_informe_planes_antiguos">
                    <thead>
                        <tr>
                            <th>Plan de Estudio</th><th>Nombre</th><th>Año Ingreso</th><th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($planes as $plan): ?>
                        
                        <tr>
                            <td><?php echo $plan['CARRERA']; ?></td>
                            <td><?php echo utf8_encode($plan['NOM_PLANESTUDIO']); ?></td>
                            <td><?php echo $plan['ANO_INGRESO']; ?></td>
                            <td><?php echo $plan['ESTADO']; ?></td>
                        </tr>
                        
                        <?php endforeach;?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Plan de Estudio</th><th>Nombre</th><th>Año Ingreso</th><th>Estado</th>
                        </tr>
                    </tfoot>
                </table>
            </div> 
        </div>
    </div>
</div>



<?php include("templates/footer.php"); ?> 