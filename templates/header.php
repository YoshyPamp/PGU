<?php include "clases/Database.php"; ?>
<?php include "clases/helpers.php"; ?>
<?php include "Config/Login/Autentificacion.php"; ?>
<?php include "Config/Login/funciones.php"; ?>
<?php 
    $db = new Database();
    session_start();
    if(basename($_SERVER['PHP_SELF']) != 'login.php'):
        if(login_check($db) == true) {
            // Add your protected page content here!
        } else { 
            header('Location: login.php');
            die();
        }
    endif;
     
?>

<!DOCTYPE html>
<html lang="es" class="full">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><-- Sistema de Gestión Universitaria --></title>
    <!-- Bootstrap Core CSS -->
    <link href="librerias/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="librerias/bootstrap/js/jquery.js"></script>
    <link href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="librerias/bootstrap/js/bootstrap.min.js"></script>
    <script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script src="js/datatables.js"></script>
    <script src="js/helpers.js"></script>
    <script type="text/javascript" src="librerias/tableExport/tableExport/libs/FileSaver/FileSaver.min.js"></script>
    <script type="text/javascript" src="librerias/tableExport/tableExport/tableExport.min.js"></script>
    <!-- Custom CSS -->
    <link href='http://fonts.googleapis.com/css?family=Arvo' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'>



    <link href="css/styles.css" rel="stylesheet">
    

</head>


<body>