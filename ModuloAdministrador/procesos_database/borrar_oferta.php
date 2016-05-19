<?php

    foreach (glob("../../clases/*.php") as $filename){
        include $filename;
    }
    
    $db = new Database();
    
    if(isset($_GET['id'])){
        
        try{
            $id = $_GET['id'];
            $db->FAM_BORRAR_OFERTA($id);
            
        } catch (Exception $ex) {
            $msg = "<div class='container alert alert-danger'>Error al borrar oferta.</div>";
            header("Location: ../admin_ofertas.php?msg='".$msg."'");
        }
        
        $msg = "<div class='container alert alert-success'>Oferta Borrada Satisfactoriamente.</div>";
        header("Location: ../admin_ofertas.php?msg='".$msg."'");
        
    }

