<?php include("templates/header.php"); ?>
<?php include("templates/navbar.php"); ?>

<?php
    if($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 3 && $_SESSION['perfil'] != 4){
        header("location: index.php");
    }
    
?>











<?php include("templates/footer.php"); ?> 