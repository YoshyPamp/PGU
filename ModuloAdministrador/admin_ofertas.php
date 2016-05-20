<?php include("header_admin.php"); ?>

<!-- ZONA DE FUNCIONES PHP -->
<?php 
    
    if(isset($_GET['ano']) && isset($_GET['semestre'])){
        $secciones = $db->FAM_SELECT_OFERTA($_GET['ano'], $_GET['semestre'], $_GET['escuela']);
        if($secciones != ''){
            
        }else{
            echo "<script>alert('No existe oferta para ese período.')</script>";
        }
    }  
    
    $escuelas = $db->FAM_SELECT_ESCUELAS();
    $selected = '';
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

<!-- ZONA DE SCRIPTS -->
<script>
    function borrar_oferta(id_oferta){
        var r = confirm("¿Está seguro que quiere borrar la oferta?");
        if (r == true) {
            window.location = "procesos_database/borrar_oferta.php?id="+id_oferta;
        }
    }
    
</script>
<!-- FIN ZONA DE SCRIPTS -->

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
        <select name="escuela" class="form-control">
            <option value="">Seleccione...</option>
            <?php foreach($escuelas as $escuela): ?>
                <option value="<?php echo $escuela['COD_ESCUELA']; ?>"><?php echo $escuela['NOM_ESCUELA']; ?></option>
            <?php endforeach;?>
        </select><br>
        <input type="submit" class="btn btn-warning" value="BUSCAR" >
    </form>
</div><br><br>

<form action="procesos_database/actualizar_oferta.php" method="POST">
    <div class="container">

        <label for="ano_oferta">Año:</label>
        <input type="number" class="form-control" name="ano_oferta" <?php echo (isset($_GET['ano']) ? '' : 'disabled'); ?> id="ano_oferta" value="<?php echo (isset($_GET['ano']) ? $_GET['ano'] : ''); ?>" placeholder="Año Oferta"><br>
        <label for="semestre_oferta">Semestre:</label>
        <input type="number" class="form-control" name="semestre_oferta" <?php echo (isset($_GET['semestre']) ? '' : 'disabled'); ?> id="semestre_oferta" value="<?php echo (isset($_GET['semestre']) ? $_GET['semestre'] : ''); ?>" placeholder="Semestre 1,2"><br>
        <label for="escuela_oferta">Escuela:</label>
        <select name="escuela_oferta" clasS="form-control">
            <option value="">Seleccione...</option>
            <?php foreach($escuelas as $escuela): ?>
                <?php if(isset($_GET['escuela'])): ?>
					<?php if($escuela['COD_ESCUELA'] == $_GET['escuela']):?>
						<?php $selected = "selected";?>
					<?php endif;?>
                <?php endif;?>
                <option <?php echo $selected; ?> value="<?php echo $escuela['COD_ESCUELA']; ?>"><?php echo $escuela['NOM_ESCUELA']; ?></option>
            <?php endforeach;?>
        </select><br>
        
    </div>
    <input type="hidden" value="<?php echo (isset($secciones[0]['OFERTA_ID']) ? $secciones[0]['OFERTA_ID'] : ''); ?>" name="codigo_oferta">
    <div class="container-fluid">

            <?php 
            if(isset($secciones) && $secciones != ''): 
                ?>

        <?php if(isset($_GET['ano'])):?>
            <div class="container">
                <input type="submit" value="ACTUALIZAR OFERTA" class="form-control btn btn-success"><br><br>
                <input value="BORRAR OFERTA" onclick="borrar_oferta(<?php echo $secciones[0]['OFERTA_ID']; ?>);" class="form-control btn btn-danger">
            </div><br><br><br>
            
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