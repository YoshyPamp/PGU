<?php include("templates/header.php"); ?>
<?php include("templates/navbar.php"); ?>


<div class="col-md-3"></div>
<div class="panel panel-default col-md-6">
    <div class="panel-heading" data-toggle="collapse" href="#collapseInformacion"  aria-controls="collapseInformacion">
        <h2 class="panel-title">Aprobación Semestral</h2>
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
          <h2 class="panel-title">Cumple</h2><br>
          <th><input type="checkbox" name="estado" value="encurso"> Bachiller</th>
          <th><input type="checkbox" name="estado" value="encurso"> Licenciatura</th>
        </div>    
      <br>
      <div>
      <table border="1" width="100%">
          <tr>
         <td align="center">I</td><td align="center">II</td><td align="center">III</td><td align="center">VI</td><td align="center">V</td>
              <td align="center">VI</td><td align="center">VII</td><td align="center">VIII</td><td align="center">IX</td><td align="center">X</td>
              <td align="center">XI</td><td align="center">XII</td>
          </tr>
          <tr>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
          </tr>
          <tr>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
          </tr>
          <tr>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
          </tr>
          <tr>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
          </tr>
          <tr>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
              <td align="center">Asignatura</td>
          </tr>
          
          
          
          </table>
      </div>    
</div>
<div class="col-md-3"></div>
    <br>
    <input align="center" type="submit" name="vovler" value="volver"/>


<?php include("templates/footer.php"); ?> 