<?php include("templates/header.php"); ?>
<?php
$seccion = $_GET['id_sec'];
$rut     = $_GET['rut'];
$ramo    = $_GET['ramo'];
$sem_ano = $_GET['ano_semestre'];

$notas = $db->FAM_SELECT_NOTAS_SECCION_BY_RUT($seccion, $rut);

?>
<div class="container">
    <table class="table table-responsive table-bordered table-hover">
        <thead>
            <tr>
                <th><em><?php echo $ramo;?></em></th><th><em><?php echo $rut;?></em></th><th><em><?php echo $sem_ano;?></em></th>
            </tr>
            <tr>
                <th>Nota</th><th>Porcentaje</th><th>Tipo Nota</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($notas as $nota): ?>
            <tr>
                <td><?php echo round($nota['NOTA'],2); ?></td>
                <td><?php echo $nota['PORCENTAJE']; ?></td>
                <td><?php echo $nota['TIPO_NOTA']; ?></td>
            </tr>
            <?php endforeach;?>
        </tbody>
        <tfoot>
            <tr>
                <th>Nota</th><th>Porcentaje</th><th>Tipo Nota</th>
            </tr>
        </tfoot>
    </table>
    <button onclick="window.close();" class="btn btn-default">CERRAR</button>
</div>

    













<?php include("templates/footer.php"); ?> 