<?php include("templates/header.php"); ?>
<?php include("templates/navbar.php"); ?>


<div class="col-md-3"></div>
<div class="panel panel-default col-md-6">
    <div class="panel-heading" data-toggle="collapse" href="#collapseInformacion"  aria-controls="collapseInformacion">
        <h2 class="panel-title">Administración y Centralización de Práctica</h2>
    </div>
  <div class="panel-body" id='collapseInformacion' >
      <div class="form-group">
          <label>RUT: </label><input type="text" value="" class="form-control" readonly>
          <p><input type="submit" name="buscar" value="buscar"/></p>
          <label>Nombre: </label>
          <input type="text" value=" " class="form-control" readonly> 
          <label>Plan Estudio: </label>
          <input type="text" value="" class="form-control" readonly>
          <label>Año de Ingreso: </label>
          <input type="text" value="" class="form-control" readonly>
      </div> 
    <div class="form-group">

          <br>
          <h2 class="panel-title">Estado de Práctica</h2><br>
          <input type="checkbox" name="estado" value="encurso"> En Curso<br>
          <input type="checkbox" name="estado" value="encurso"> Cursada<br>
          <input type="checkbox" name="estado" value="encurso"> No Cursada<br>
      </div>    
      <br>
      <div>
      <label>Comentarios:<br>
      <textarea name="comentario" cols="70" rows="8" id="comentario" class="form-control" readonly></textarea></label>
      <p><input type="submit" name="enviarcomentario" value="guardar"/></p>
      </div>    
</div>
<div class="col-md-3"></div>
    <input align="center" type="submit" name="vovler" value="volver"/>


<?php include("../templates/footer.php"); ?> 