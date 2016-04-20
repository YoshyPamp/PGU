<?php
include("templates/header.php");
include("templates/navbar.php");
include_once 'Config/login/Autentificacion.php';
include_once 'Config/login/funciones.php';

if (login_check($db) == true) {
    $logged = 'in';
} else {
    $logged = 'out';
}
?>
<script>
    function activar_alumno(value){
        if(value === 'ALUMNO'){
            $('#rut').removeAttr('disabled');
        }else{
            $('#rut').attr('disabled','disabled');
        }
    }

    function validatePassword(){
        
        
        event.preventDefault();
        
        var form = document.getElementById('registro');
        var password = $('#p');
        var confirm_password = $('#p2');
        var email = $('#email').val();
        var dominio = document.getElementById('dominio').value;
        var tipo = document.getElementById('tipo').value;
        var rut = $('#rut').val();
  
        if(password.val() != confirm_password.val()) {
          alert("Contraseñas no coinciden");
          confirm_password.val('');
        } else {
            if(email != "" && dominio != "" && tipo != "" && password.val() != ""){
                if(tipo == "ALUMNO"){
                    if(rut != ""){
                        form.submit();
                    }else{
                        alert('Favor ingresar RUT.');
                    }
                }else{
                    form.submit();
                }
            }else{
                alert('Favor completar campos faltante.')
            }
        }
    }
    
</script>
    <body>
        <?php if(isset($_GET['msg'])): ?>
            <?php echo $_GET['msg']; ?>
        <?php endif;?>
        <div class="container">
            <div class="row">
                <form action="Config/Login/login.php" method="post" name="login_form">
                    <div class="form-group">
                        <div class="col-sm-10">
                              <label for="email">Correo:</label>
                              <input type="email" name="correo" class="form-control" id="correo" placeholder="Ingrese correo..." required>
                        </div>
                        <div class="col-md-12" style="height: 15px;"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-10"> 
                            <label class="control-label" for="p">Password:</label>
                            <input type="password" class="form-control" name="p" id="password" placeholder="Ingrese Contraseña..." required>
                        </div>
                        <div class="col-md-12" style="height: 15px;"></div>
                    </div>
                    <div class="form-group"> 
                        <div class="col-sm-10">
                            <button type="submit" value="Login" class="btn btn-info">INICIAR</button>
                            <button type="button" value="Registro" class="btn btn-default" onclick="$('#registro').toggle();">REGISTRARME</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-12" style="height: 50px;"></div>
            <div class="row">
                <form id="registro" style="display: none;" method="post" action="registrar_usuario.php">
                    <div class="form-group">
                        <div class="col-sm-10">
                            <label class="control-label col-sm-6" for="email"><e>(Correo eléctronico sin el @mayor)</e>:</label>
                            <input type="text" id="email" name="email" class="form-control" id="email" placeholder="Ej: andres.diaz" required>
                            <div class="col-md-12" style="height: 15px;"></div>
                            <select class="form-control" required name="dominio" id="dominio">
                                <option value="">Seleccione dominio...</option>
                                <option value="@mayor.cl">@mayor.cl</option>
                                <option value="@umayor.cl">@umayor.cl</option>
                            </select>
                            <div class="col-md-12" style="height: 15px;"></div>
                        </div>
                        <div class="col-sm-10">
                            <label class="control-label col-sm-2" for="tipo">Tipo Usuario:</label>
                            <select class="form-control" onchange="activar_alumno(this.value);" name="tipo" id="tipo" required>
                                <option value="">Seleccione opción...</option>
                                <option value="ALUMNO">Alumno</option>
                            </select>
                            <div class="col-md-12" style="height: 15px;"></div>
                            <label class="control-label col-sm-2" for="p">Contraseña:</label>
                            <input type="password" class="form-control" name="p" id="p" placeholder="Ingrese contraseña..." required>
                            <div class="col-md-12" style="height: 5px;"></div>
                            <input type="password" class="form-control" name="p2" id="p2" placeholder="Confirme contraseña..." required>
                        </div>
                        <div class="col-md-12" style="height: 15px;"></div>
                        <div class="col-sm-10">
                            <label class="control-label col-sm-6" for="rut">Rut:</label>
                            <input type="text" class="form-control" name="rut" id="rut" placeholder="Ej: 168956537" disabled required>
                            <div class="col-md-12" style="height: 15px;"></div>
                            <button type="submit" onclick="validatePassword(event);" class="btn btn-info">Registrar</button>
                        </div>
                  </div>
                </form>
            </div>  
        </div>
    </body>
</html>