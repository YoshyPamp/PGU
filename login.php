<?php
include("templates/header.php");
include("templates/navbar.php");
include_once 'Config/login/Autentificacion.php';
include_once 'Config/login/funciones.php';

 
sec_session_start();
 
if (login_check($db) == true) {
    $logged = 'in';
} else {
    $logged = 'out';
}
?>
    <body>
        <?php
        if (isset($_GET['error'])) {
            echo '<p class="error">Error en login!</p>';
        }
        ?> 
        <div class="container">
            <form class="form-horizontal" action="Config/Login/login.php" method="post" name="login_form">
                <div class="form-group">
                  <label class="control-label col-sm-2" for="email">Email:</label>
                  <div class="col-sm-10">
                    <input type="email" name="email" class="form-control" id="email" placeholder="Ingrese correo...">
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-sm-2" for="pwd">Password:</label>
                  <div class="col-sm-10"> 
                    <input type="password" class="form-control" name="password" id="password" placeholder="Ingrese Contraseña...">
                  </div>
                </div>
                <div class="form-group"> 
                  <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" value="Login" class="btn btn-info">Submit</button>
                  </div>
                </div>
              </form>
            <div class="row">
            </div>  
        </div>
    </body>
</html>