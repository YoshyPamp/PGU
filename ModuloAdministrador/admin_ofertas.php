<?php include("header_admin.php"); ?>

<!-- ZONA DE FUNCIONES PHP -->
<?php 
    
    if(isset($_GET['ano']) && isset($_GET['semestre'])){
        $secciones = $db->FAM_SELECT_OFERTA($_GET['ano'], $_GET['semestre']);
        if($secciones != ''){
            
        }else{
            echo "<script>alert('No existe oferta para ese período.')</script>";
        }
    }  
?>
<!-- FIN ZONA DE FUNCIONES PHP -->


<!-- ZONA DE CABECERA -->
    <nav class="navbar navbar-default"> 
        <div class="loader"></div>
        <a class="navbar-brand logo" href="../index.php">
            <img alt="Brand"  src="../Imagenes/logo-U.jpg">
        </a>
        <h2>ADMINISTRADOR DE OFERTAS</h2>    
    </nav>
<!-- FIN ZONA DE CABECERA -->


<!-- ZONA DE MENSAJES -->
<?php
    if(isset($_GET['msg'])){
        echo $_GET['msg'];
    }
?>
<!-- FIN ZONA DE MENSAJES -->


<!-- ZONA DE BUSQUEDA Y RESULTADO -->
<div class="container">
    <form action="" method="GET" name="forma_oferta" id="forma_oferta">
        <legend>Buscar oferta a modificar.</legend>
        <input type="number" class="form-control" name="ano" id="ano" placeholder="Año Oferta" required><br>
        <input type="number" class="form-control" name="semestre" id="semestre" placeholder="Semestre 1,2" required><br>
        <input type="submit" class="btn btn-warning" value="BUSCAR" >
    </form>
</div><br><br>

<form action="procesos_database/actualizar_oferta.php" method="POST">
    <div class="container">

        <label for="ano_oferta">Año:</label>
        <input type="number" class="form-control" name="ano_oferta" <?php echo (isset($_GET['ano']) ? '' : 'disabled'); ?> id="ano_oferta" value="<?php echo (isset($_GET['ano']) ? $_GET['ano'] : ''); ?>" placeholder="Año Oferta"><br>
        <label for="semestre_oferta">Semestre:</label>
        <input type="number" class="form-control" name="semestre_oferta" <?php echo (isset($_GET['semestre']) ? '' : 'disabled'); ?> id="semestre_oferta" value="<?php echo (isset($_GET['semestre']) ? $_GET['semestre'] : ''); ?>" placeholder="Semestre 1,2"><br>
    </div>
    <input type="hidden" value="<?php echo (isset($secciones[0]['OFERTA_ID']) ? $secciones[0]['OFERTA_ID'] : ''); ?>" name="codigo_oferta">
    <div class="container-fluid">

            <?php 
            if(isset($secciones) && $secciones != ''): 
                ?>

        <?php if(isset($_GET['ano'])):?>
            <input type="submit" value="ACTUALIZAR OFERTA" class="form-control btn btn-success"><br><br><br>
        <?php endif;?>
        <table id="oferta_table" class="table table-striped table-bordered" width="100%" >
            <thead>
                <tr>
                    <th>ID</th><th>Código Sección</th><th>Profesor</th><th>Inscritos</th><th>Cupos</th><th>Capacidad</th><th>Día</th><th>Inicio</th><th>Término</th><th>Modalidad</th>
                </tr>
            </thead>
            <tbody>
                <?php
                     foreach($secciones as $seccion):
                        ?>
                <tr>
                    <td><?php echo $seccion['ID_SECCION'] ?></td>
                    <td><input type="text" class="form-control" value="<?php echo $seccion['COD_SECCION'] ?>" name="<?php echo $seccion['ID_SECCION'] ?>[]" /></td>
                    <td><input type="text" class="form-control" value="<?php echo utf8_encode($seccion['PROFESOR_NOMBRE']) ?>" name="<?php echo $seccion['ID_SECCION'] ?>[]" /></td>
                    <td><input type="text" class="form-control" value="<?php echo $seccion['INSCRITOS'] ?>" name="<?php echo $seccion['ID_SECCION'] ?>[]" /></td>
                    <td><input type="text" class="form-control" value="<?php echo $seccion['CUPOS'] ?>" name="<?php echo $seccion['ID_SECCION'] ?>[]" /></td>
                    <td><input type="text" class="form-control" value="<?php echo $seccion['CAPACIDAD'] ?>" name="<?php echo $seccion['ID_SECCION'] ?>[]" /></td>
                    <td><input type="text" class="form-control" value="<?php echo utf8_encode($seccion['DIA']) ?>" name="<?php echo $seccion['ID_SECCION'] ?>[]" /></td>
                    <td><input type="text" class="form-control" value="<?php echo $seccion['INICIO'] ?>" name="<?php echo $seccion['ID_SECCION'] ?>[]" /></td>
                    <td><input type="text" class="form-control" value="<?php echo $seccion['TERMINO'] ?>" name="<?php echo $seccion['ID_SECCION'] ?>[]" /></td>
                    <td><input type="text" class="form-control" value="<?php echo $seccion['MODALIDAD'] ?>" name="<?php echo $seccion['ID_SECCION'] ?>[]" /></td>
                </tr>

                        <?php
                     endforeach; 
                     ?>
                </tbody>
            <tfoot>
                <tr>
                    <th>ID</th><th>Código Sección</th><th>Profesor</th><th>Inscritos</th><th>Cupos</th><th>Capacidad</th><th>Día</th><th>Inicio</th><th>Término</th><th>Modalidad</th>
                </tr>
            </tfoot>
        </table>

            <?php
            endif; ?>   

    </div>
</form>
<!-- FIN ZONA DE BUSQUEDA Y RESULTADO -->

<?php include("../templates/footer.php"); ?>