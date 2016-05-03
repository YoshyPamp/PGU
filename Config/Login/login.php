<?php
include '../../clases/Database.php';
include 'Autentificacion.php';
include 'funciones.php';

$db = new Database();

if (isset($_POST['correo'], $_POST['p'])) {
    $email = $_POST['correo'];
    $password = $_POST['p']; // The hashed password.
 
    switch(login($email, $password, $db)) {
        case 0:
            var_dump($_SESSION);
            header('Location: ../../index.php');
            break;
        case -1:
            $msg = '<div class="alert alert-danger"><strong>Error!</strong> Combinación de email y contraseña incorrecta.</div>';
            header('Location: ../../login.php?msg='.$msg);
            break;
        case -2:
            $msg = '<div class="alert alert-danger"><strong>Error!</strong> Cuenta a la que se hace referencia se encuentra bloqueada.</div>';
            header('Location: ../../login.php?msg='.$msg);
            break;
        case -3:
            $msg = '<div class="alert alert-danger"><strong>Error!</strong> Cuenta no existe, favor registrarse.</div>';
            header('Location: ../../login.php?msg='.$msg);
            break;
        default:
            break;
    }
} else {
    
    if(isset($_POST['pass1']))
    
    
    // Las variables no fueron enviadas por POST. 
    echo 'Invalid Request';
}

