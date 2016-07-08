<?php
	
    foreach (glob("../clases/*.php") as $filename)
    {
        include $filename;
    }
?>
<?php include_once "../Config/Login/Autentificacion.php"; ?>
<?php include_once "../Config/Login/funciones.php"; ?>
<?php
    session_start();

    

    $db = new Database();
    if(basename($_SERVER['PHP_SELF']) != 'login.php'):
        if(login_check($db) == true) {
            // Add your protected page content here!
        } else { 
                header("location: ../login.php");
                die();
        }
    endif;
	
	if($_SESSION['perfil'] != 1){
            header("location: /../index.php");
        }
    
?>

<!DOCTYPE html>
<html class="full">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <title><-- Sistema de Gestión Universitaria --></title>
        <!-- Bootstrap Core CSS -->
        <link href="../librerias/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
        <link href="//cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css" rel="stylesheet">
        <script src="../librerias/bootstrap/js/bootstrap.min.js"></script>
        <script src="//cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
        <script src='../js/helpers.js'></script>
        <script type="text/javascript" src="../librerias/tableExport/tableExport/libs/FileSaver/FileSaver.min.js"></script>
        <script type="text/javascript" src="../librerias/tableExport/tableExport/tableExport.min.js"></script>
        <!-- Custom CSS -->
        <link href='http://fonts.googleapis.com/css?family=Arvo' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'>



        <link href="../css/styles.css" rel="stylesheet">
    </head>
    
    <nav class="navbar navbar-default nvb">       
        <a class="navbar-brand logo" href="../index.php">
            <img alt="Brand"  src="../Imagenes/logo-U.jpg">
        </a>

        <h2>SISTEMA DE GESTIÓN UNIVERSITARIO</h2>    
    </nav>
    <div class="container-fluid">
        <a href="../index.php?activo=reporte"><img src="../Imagenes/back.png" width="30px" heigth="30px"></a>
    </div>

