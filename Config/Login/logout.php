<?php
include_once 'Autentificacion.php';
include_once 'funciones.php';
 
    session_start();
    // Unset all session values 
    unset($_SESSION);

    // Destroy session 
    session_destroy();
    header('Location: ../../login.php');
