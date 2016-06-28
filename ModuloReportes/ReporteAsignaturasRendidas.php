<?php include("header_reportes.php"); ?> 


<div class="col-md-3"></div>
<div class="panel panel-default col-md-6">
    <div class="panel-heading" data-toggle="collapse" href="#collapseInformacion"  aria-controls="collapseInformacion">
        <h2 class="panel-title">Asignaturas Rendidas</h2>
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
    </div>
<div class="col-md-3"></div>
    
<div class="panel panel-default col-md-12">
    <div class="panel-heading" data-toggle="collapse" href="#collapseHorario" aria-controls="collapseHorario">
        <h2 class="panel-title">Asignaturas Rendidas </h2>
    </div>
  <div class="panel-body collapse" id='collapseHorario'>
    <table class="table table-condensed" >
        <th>Código</th><th>Nombre Asignatura</th><th>Promedio</th><th>Notas</th>
            <tr><td>ICV4560</td><td><p>Biología</p></td><td><p>Homologada</p></td><td><p><input type="submit" name="buscar" value="ver"/></td></p></tr>
            <tr><td>ICV4560</td><td><p>Biología</p></td><td><p>Homologada</p></td><td><p><input type="submit" name="buscar" value="ver"/></td></p></tr>
            <tr><td>ICV4560</td><td><p>Biología</p></td><td><p>Homologada</p></td><td><p><input type="submit" name="buscar" value="ver"/></td></p></tr>
            <tr><td>ICV4560</td><td><p>Biología</p></td><td><p>Homologada</p></td><td><p><input type="submit" name="buscar" value="ver"/></td></p></tr>
            <tr><td>ICV4560</td><td><p>Biología</p></td><td><p>Homologada</p></td><td><p><input type="submit" name="buscar" value="ver"/></td></p></tr>    
        </tr>
                
    </table>

  </div>
</div>
<input align="center" type="submit" name="vovler" value="volver"/>


<?php include("../templates/footer.php"); ?> 